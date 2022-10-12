<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class HaircolorOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Blond'), 'value' => '1'],
                                ['label' => __('Brown'), 'value' => '2'],
                                ['label' => __('Grey'), 'value' => '3'],
                                ['label' => __('Colored'), 'value' => '4'],
                                ['label' => __('Brun'), 'value' => '5'],
                                ['label' => __('Gris'), 'value' => '6'],
                                ['label' => __('Colorés'), 'value' => '7']
                            ];
        }

        return $this->_options;
    }
}