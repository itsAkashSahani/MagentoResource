<?php

namespace Albatool\Checkout\Plugin;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Albatool\Checkout\Block\ProductGift;

class DefaultConfigProviderPlugin
{
	/**
    * Get country path
    */
    const COUNTRY_CODE_PATH = 'general/country/default';
	
	/**
    *@var checkoutSession
    */
    protected $checkoutSession;
	
    /**
    * @var ScopeConfigInterface
    */
    private $scopeConfig;
	
	/**
    * @var ProductRepositoryInterface
    */
    private $productRepository;
	
	/**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;

    protected $helper;

    private $sourceItemsBySku;

    private $sourceRepository;
    private $productGift;

	/**
    * Constructor
    * @param CheckoutSession $checkoutSession
    * @param ScopeConfigInterface $scopeConfig
    * @param ProductRepositoryInterface $productRepository
    * @param Magento\Checkout\Helper\Data $checkoutHelper
    */
	public function __construct(
        ScopeConfigInterface $scopeConfig,
		CheckoutSession $checkoutSession,
		ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Helper\Data $checkoutHelper,
        \Albatool\Checkout\Helper\Data $helper,
        \Magento\InventoryApi\Api\GetSourceItemsBySkuInterface $sourceItemsBySku,
        \Magento\InventoryApi\Api\SourceRepositoryInterface $sourceRepository,
        ProductGift $productGift

    ) {
        $this->scopeConfig = $scopeConfig;
		$this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->checkoutHelper = $checkoutHelper;
        $this->helper = $helper;
        $this->sourceItemsBySku = $sourceItemsBySku;
        $this->sourceRepository = $sourceRepository;
        $this->productGift = $productGift;
    }
	
	/**
     * Get Config value
     *
     * @return array
     */
    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
		array $result 
    ) {
        $result['store_country_id'] = $this->getCountryByWebsite();
		$items = $result['totalsData']['items'];

        $giftCount = $this->productGift->getGiftCountById($items);
        $result['totalsData']['giftCount'] = $giftCount;

        $result['isPickupEnabled'] = $this->helper->isPickupStoreEnabled();

        foreach ($items as $index => $item) {
            $quoteItem = $this->checkoutSession->getQuote()->getItemById($item['item_id']);
			$product = $this->productRepository->get($quoteItem->getProduct()->getSku());

			$result['totalsData']['items'][$index]['available_home_delivery'] = $product->getAvailableHomeDelivery();
            $result['totalsData']['items'][$index]['tube_qty'] = $product->getTubeQty();
            $result['totalsData']['items'][$index]['quote_item_sku'] = $quoteItem->getProduct()->getSku();
            if($product->getColorShades()){
                $prodColorShades = " . ".$product->getColorShades(); 
            }
            else
            {
                $prodColorShades = "";  
            }
            $result['totalsData']['items'][$index]['color_shades'] = $prodColorShades;
            $result['totalsData']['items'][$index]['prod_other_price'] = $product->getProdOtherPrice();

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $regular_price = $product->getPriceInfo()->getPrice('regular_price')->getAmount()->getValue();
            $special_price = $product->getPriceInfo()->getPrice('special_price')->getAmount()->getValue();
            $tier_price = $product->getPriceInfo()->getPrice('tier_price')->getAmount()->getValue();

            $rule = $objectManager->create('\Magento\CatalogRule\Model\RuleFactory')->create();
            $discountAmount = $rule->calcProductPriceRule($product,$regular_price);
            $allPrices = [];
            array_push($allPrices, $regular_price);
                
            if($discountAmount){ //echo "discounted";
                array_push($allPrices, $discountAmount);
            }

            if($special_price) {
                array_push($allPrices, $special_price);
            }

            if($tier_price) {
                array_push($allPrices, $tier_price);
            }

			if($product->getSpecialPrice() || $discountAmount || $product->getTierPrice()){
				$result['totalsData']['items'][$index]['special_price'] = min($allPrices);
				$result['totalsData']['items'][$index]['old_price'] = $regular_price;
				$result['totalsData']['items'][$index]['special_price_show'] = true;
			}else{
				$result['totalsData']['items'][$index]['special_price_show'] = false;
				$result['totalsData']['items'][$index]['old_price'] = $regular_price;
				$result['totalsData']['items'][$index]['special_price'] = '';
			}
            $total_source_list = array();
            $sourceList = $this->getSourcesList();
            foreach ($sourceList as $source) {
                if($source['source_code'] != 'default'){
                    $total_source_list[] = $source['source_code'];
                }
            }       
            $sourceItemList = $this->getSourceItemBySku($quoteItem->getProduct()->getSku());
            $sourceListAvail = array();
            foreach ($sourceItemList as $source) {
                if($source['source_code'] != 'default'){
                    if($source['quantity'] == "0.00"){
                        $sourceListAvail[] = $source['source_code'];
                    }
                }
            }
            $total_exist_source = array();
            foreach($total_source_list as $total_source){
                if($total_source != 'default'){
                    if(!in_array($total_source, $sourceListAvail)){
                        $total_exist_source[] = $total_source;    
                    }
                }
            }
            $result['totalsData']['items'][$index]['source_prod_qty'] = !empty($sourceListAvail)?$sourceListAvail:'';
        }

        return $result;
    }

    public function getSourceItemBySku($sku)
    {
       return $this->sourceItemsBySku->execute($sku);
    }

    public function getSourcesList()
    {
        $sourceData = $this->sourceRepository->getList();
        return $sourceData->getItems();
    }
 
	/**
     * Get Country code by website scope
     *
     * @return string
     */
    public function getCountryByWebsite(): string
    {
        return $this->scopeConfig->getValue(
            self::COUNTRY_CODE_PATH,
            ScopeInterface::SCOPE_WEBSITES
        );
    }
} 