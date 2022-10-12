<?php

namespace Albatool\CustomSortBy\Plugin\Catalog\Model;

class Config
{
    public function afterGetAttributeUsedForSortByArray(\Magento\Catalog\Model\Config $catalogConfig, $options)
    {
        $options['whats_new'] = __("What's New");
        $options['low_to_high'] = __('Price - Low To High');
        $options['high_to_low'] = __('Price - High To Low');
        $options['by_discount'] = __('Discount');
        //unset($options['position']);
        unset($options['name']);
        unset($options['price']);

        return $options;
    }
}