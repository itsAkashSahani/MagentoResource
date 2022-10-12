<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class AreaOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Complexion'), 'value' => '1'],
                                ['label' => __('Eyes & Eyebrows'), 'value' => '2'],
                                ['label' => __('Lips'), 'value' => '3'],
                                ['label' => __('Nails'), 'value' => '4'],
                                ['label' => __('Teint'), 'value' => '5'],
                                ['label' => __('Yeux et Sourcils'), 'value' => '6'],
                                ['label' => __('Lèvres'), 'value' => '7'],
                                ['label' => __('Ongles'), 'value' => '8'],
                                ['label' => __('body'), 'value' => '9'],
                                ['label' => __('Hands'), 'value' => '10'],
                                ['label' => __('Feet Legs'), 'value' => '11'],
                                ['label' => __('Corps'), 'value' => '12'],
                                ['label' => __('Mains'), 'value' => '13'],
                                ['label' => __('Jambes et pieds'), 'value' => '14'],
                                ['label' => __('Face'), 'value' => '15'],
                                ['label' => __('Visage'), 'value' => '16']
                            ];
        }

        return $this->_options;
    }
}