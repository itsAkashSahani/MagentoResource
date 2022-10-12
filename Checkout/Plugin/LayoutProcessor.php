<?php

namespace Albatool\Checkout\Plugin;

class LayoutProcessor
{
    /**
     * @param LayoutProcessor $subject
     * @param array $result
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $result
    ) {
        $result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['city'] = $this->getConfig();

        unset($result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['region_id']);

        unset($result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['postcode']);

        return $result;
    }

    /**
     * @return $field
     */
    private function getConfig()
    {
        $field = [
            'component' => 'Albatool_Checkout/js/city',
            'config' => [
                'customScope' => 'shippingAddress',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/select',
                'id' => 'city'
            ],
            'label' => 'City',
            'value' => '',
            'dataScope' => 'shippingAddress.city',
            'provider' => 'checkoutProvider',
            'sortOrder' => 80,
            'customEntry' => null,
            'visible' => true,
            'options' => [ ],
            'filterBy' => [
                'target' => '${ $.provider }:${ $.parentScope }.country_id',
                'field' => 'country_id'
            ],
            'validation' => [
                'required-entry' => true
            ],
            'id' => 'city',
            'imports' => [
                'initialOptions' => 'index = checkoutProvider:dictionaries.city',
                'setOptions' => 'index = checkoutProvider:dictionaries.city'
            ]
        ];


        return $field;
    }
}