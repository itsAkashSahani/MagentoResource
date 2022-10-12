<?php

namespace Albatool\Checkout\Plugin\Checkout;

use Magento\Quote\Model\Quote\Item;

class CartItem
{

    /**
     * @var \Magento\Checkout\Helper\Data
     */
    protected $checkoutHelper;

	/**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;
	
    public function __construct(
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Checkout\Helper\Data $checkoutHelper
    ) {
        $this->productRepository = $productRepository;
        $this->checkoutHelper = $checkoutHelper;
    }
	
    public function afterGetItemData($subject,$result)
    {
        $product = $this->productRepository->get($result['product_sku']);

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
		$atts = array();
		$price = $regular_price * $result['qty'];
		if($product->getSpecialPrice() || $discountAmount || $product->getTierPrice()){
			$atts = [
				"special_price" => $this->checkoutHelper->formatPrice(min($allPrices)),
				"saving_price_value" => $price - (min($allPrices) * $result['qty']),
				"show_special_price" => true,
				"price" => $this->checkoutHelper->formatPrice($regular_price),
				"total_special_price" => $result['product_price'],
				"total_price" => $price,

			];
		}else{
			$atts = [
					"saving_price_value" => 0,
					"special_price" => "",
					"show_special_price" => false,
					"price" => $this->checkoutHelper->formatPrice($product->getPrice()),

				];
		}
        return array_merge($result,$atts);
    }
}