<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class SkintypeOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Normal skin'), 'value' => '1'],
                                ['label' => __('Combination oily skin'), 'value' => '2'],
                                ['label' => __('Dry dehydrated skin'), 'value' => '3'],
                                ['label' => __('Sensitive skin'), 'value' => '4'],
                                ['label' => __('Acne prone skin'), 'value' => '5'],
                                ['label' => __('All skin types'), 'value' => '6'],
                                ['label' => __('Mature skin'), 'value' => '7'],
                                ['label' => __('Peaux normales à mixtes'), 'value' => '8'],
                                ['label' => __('Peaux  mixtes à grasses'), 'value' => '9'],
                                ['label' => __('Peaux sèches à très sèches'), 'value' => '10'],
                                ['label' => __('Peaux sensibles'), 'value' => '11'],
                                ['label' => __('Peaux à tendances acnéiques'), 'value' => '12'],
                                ['label' => __('Tous types de peau'), 'value' => '13'],
                                ['label' => __('Peaux matures'), 'value' => '14']
                            ];
        }

        return $this->_options;
    }
}