<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class LimitededitionOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('60 Years Anniversary'), 'value' => '1'],
                                ['label' => __('Anniversaire 60 ans'), 'value' => '2'],
                                ['label' => __('Nuit Vanille'), 'value' => '3'],
                                ['label' => __('Green Summer'), 'value' => '4'],
                                ['label' => __('Festive Makeup'), 'value' => '5'],
                                ['label' => __('Good vibes only'), 'value' => '6'],
                                ['label' => __('Hello Spring'), 'value' => '7'],
                                ['label' => __('Maquillage de fêtes'), 'value' => '8'],
                                ['label' => __('Hello Printemps'), 'value' => '9'],
                                ['label' => __('At the heart of pinetrees'), 'value' => '10'],
                                ['label' => __('First snowflakes'), 'value' => '11'],
                                ['label' => __('Festive makeup'), 'value' => '12'],
                                ['label' => __('Au cœur des sapins'), 'value' => '13'],
                                ['label' => __('Premiers flocons'), 'value' => '14']
                            ];
        }

        return $this->_options;
    }
}