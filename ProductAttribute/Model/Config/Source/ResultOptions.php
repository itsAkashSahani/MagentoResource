<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class ResultOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Curling'), 'value' => '1'],
                                ['label' => __('Lengthening'), 'value' => '2'],
                                ['label' => __('Waterproof'), 'value' => '3'],
                                ['label' => __('Volumizing'), 'value' => '4'],
                                ['label' => __('Recourbant'), 'value' => '5'],
                                ['label' => __('Allongeant'), 'value' => '6'],
                                ['label' => __('Waterproof'), 'value' => '7'],
                                ['label' => __('Volumateur'), 'value' => '8']
                            ];
        }

        return $this->_options;
    }
}