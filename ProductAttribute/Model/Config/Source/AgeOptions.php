<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class AgeOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Under 20 years'), 'value' => '1'],
                                ['label' => __('Moins de 20 ans'), 'value' => '2'],
                                ['label' => __('From 20 to 30 years'), 'value' => '3'],
                                ['label' => __('De 20 à 30 ans'), 'value' => '4'],
                                ['label' => __('From 30 to 40 years'), 'value' => '5'],
                                ['label' => __('De 30 à 40 ans'), 'value' => '6'],
                                ['label' => __('From 40 to 50 years'), 'value' => '7'],
                                ['label' => __('De 40 à 50 ans'), 'value' => '8'],
                                ['label' => __('From 50 to 60 years'), 'value' => '9'],
                                ['label' => __('De 50 à 60 ans'), 'value' => '10'],
                                ['label' => __('Over 60 years'), 'value' => '11'],
                                ['label' => __('60 ans et plus'), 'value' => '12']
                            ];
        }

        return $this->_options;
    }
}