<?php

namespace Albatool\Wishlist\Plugin\Wishlist\CustomerData;

use Magento\Framework\App\Http\Context;
use Magento\Wishlist\Helper\Data as HelperWishlist;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Wishlist\Helper\Data as HelperData;

class WishlistPlugin
{

    /**
     * @var HelperData
     */
    private $wishlistHelper;

    /**
     * WishlistPlugin constructor.
     *
     * @param HelperData $wishlistHelper
     */
    public function __construct(
        HelperData $wishlistHelper
    ) {
        $this->wishlistHelper = $wishlistHelper;
    }

    /**
     * @param $subject
     * @param $result
     * @return array
     */
    public function afterGetSectionData($subject, $result)
    {
        if (is_array($result)) {
            $result['wishlist_ids'] = $this->getItemIds();
        }

        return $result;
    }

    /**
     * Get wishlist item ids
     *
     * @return array
     */
    protected function getItemIds()
    {
        $ids = [];
        $collection = $this->wishlistHelper->getWishlistItemCollection();

        $collection->clear()->setPageSize($collection->getSize())->setInStockFilter(true)->setOrder('added_at');

        foreach($collection->getItems() as $item) {
            array_push($ids, $item->getProduct()->getId());
        }

        return $ids;
    }
}