<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class ScentOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Fruity'), 'value' => '1'],
                                ['label' => __('Sensual'), 'value' => '2'],
                                ['label' => __('Citrus'), 'value' => '3'],
                                ['label' => __('Floral'), 'value' => '4'],
                                ['label' => __('Argan & Rose'), 'value' => '5'],
                                ['label' => __('Fruité'), 'value' => '6'],
                                ['label' => __('Sensuel'), 'value' => '7'],
                                ['label' => __('Agrumes'), 'value' => '8']
                            ];
        }

        return $this->_options;
    }
}