<?php

namespace Albatool\Checkout\Plugin;

class TotalRepositoryPlugin
{

    /**
     * @param \Magento\Authorization\Model\UserContextInterface $userContext
     * @param \Hexcrypto\WishlistAPI\Helper\Data $wishlistHelper
     */
    public function __construct(
        \Magento\Quote\Model\Quote\ItemFactory $itemFactory,
        \Magento\Quote\Api\Data\TotalsItemExtensionFactory $totalItemExtensionFactory    
    ) {
        $this->itemFactory = $itemFactory;
        $this->totalItemExtension = $totalItemExtensionFactory;

    }

    /**
     * add sku in total cart items
     *
     * @param  \Magento\Quote\Api\CartTotalRepositoryInterface $subject
     * @param  \Magento\Quote\Api\Data\TotalsInterface $totals
     * @return \Magento\Quote\Api\Data\TotalsInterface $totals
     */
    public function afterGet(
        \Magento\Quote\Api\CartTotalRepositoryInterface $subject,
        \Magento\Quote\Api\Data\TotalsInterface $totals
    ) {
        foreach($totals->getItems() as $item)
        {
            $quoteItem = $this->itemFactory->create()->load($item->getItemId());
            $extensionAttributes = $item->getExtensionAttributes();
            if ($extensionAttributes === null) {
                $extensionAttributes = $this->totalItemExtension->create();
            }
            $extensionAttributes->setSku($quoteItem->getSku());
            $item->setExtensionAttributes($extensionAttributes);
        }

        return $totals;
    }

}