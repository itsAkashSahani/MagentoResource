<?php

namespace Albatool\SummaryCheckout\Plugin;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Api\CartItemRepositoryInterface as QuoteItemRepository;


class ConfigProviderPlugin extends \Magento\Framework\Model\AbstractModel
{
    private $checkoutSession;
    private $quoteItemRepository;
    protected $scopeConfig;

    public function __construct(
        CheckoutSession $checkoutSession,
        QuoteItemRepository $quoteItemRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->checkoutSession = $checkoutSession;
        $this->quoteItemRepository = $quoteItemRepository;
    }


    public function afterGetConfig(\Magento\Checkout\Model\DefaultConfigProvider $subject, array $result)
    {
            $quoteId = $this->checkoutSession->getQuote()->getId();            
            if ($quoteId) {            
                $itemOptionCount = count($result['totalsData']['items']);
                $quoteItems = $this->quoteItemRepository->getList($quoteId);
                $isbnOptions = array();
                foreach ($quoteItems as $index => $quoteItem) {
                    $quoteItemId = $quoteItem['item_id'];
                    $isbnOptions[$quoteItemId] = $quoteItem['isbn'];               
                }

                for ($i=0; $i < $itemOptionCount; $i++) {
                $quoteParentId = $result['totalsData']['items'][$i]['item_id'];
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $productId = $result['quoteItemData'][$i]['product']['entity_id'];
                $productObj = $objectManager->create('\Magento\Catalog\Model\Product')->load($productId);

                $productFlavours = $productObj->getTubeQty();       
                if($productFlavours == 'No' || $productFlavours == 'NA'){
                   $productFlavours = '';
                }
                $result['quoteItemData'][$i]['tube_qty'] = $productFlavours;
                if($productObj->getColorShades()){
                    $prodColorShades = " . ".$productObj->getColorShades(); 
                }
                else
                {
                   $prodColorShades = "";  
                }
                $result['quoteItemData'][$i]['color_shades'] = $prodColorShades;
                $result['quoteItemData'][$i]['prod_other_price'] = $productObj->getProdOtherPrice();
                if($productObj->getSpecialPrice()){
                    $quote_qty = $result['totalsData']['items'][$i]['qty'];
                    $quote_price = $productObj->getPrice();
                    $quote_original_price = ($quote_qty*$quote_price);
                    $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data'); 
                    $result['quoteItemData'][$i]['prod_old_price'] = $priceHelper->currency($quote_original_price, true, false);
                }
                else
                {
                    $result['quoteItemData'][$i]['prod_old_price'] = '';
                }
                json_encode($result);
                }
        }
        return $result;
        }

}