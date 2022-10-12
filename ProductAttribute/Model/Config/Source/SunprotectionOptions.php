<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class SunprotectionOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Sun protection factor 6'), 'value' => '1'],
                                ['label' => __('Sun protection factor 15'), 'value' => '2'],
                                ['label' => __('Sun protection factor 20'), 'value' => '3'],
                                ['label' => __('Sun protection factor 30'), 'value' => '4'],
                                ['label' => __('Sun protection factor 50+'), 'value' => '5'],
                                ['label' => __('Indice de protection 6'), 'value' => '6'],
                                ['label' => __('Indice de protection 15'), 'value' => '7'],
                                ['label' => __('Indice de protection 20'), 'value' => '8'],
                                ['label' => __('Indice de protection 30'), 'value' => '9'],
                                ['label' => __('Indice de protection 50+'), 'value' => '10'],
                                ['label' => __('No protection'), 'value' => '11'],
                                ['label' => __('Sans protection'), 'value' => '12']
                            ];
        }

        return $this->_options;
    }
}