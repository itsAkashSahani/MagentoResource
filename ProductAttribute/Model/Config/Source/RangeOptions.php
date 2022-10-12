<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class RangeOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Elixir jeunesse'), 'value' => '1'],
                                ['label' => __('Hydra Végétal'), 'value' => '2'],
                                ['label' => __('Sébo Végétal'), 'value' => '3']
                            ];
        }

        return $this->_options;
    }
}