<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class FormatOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Mini format'), 'value' => '1'],
                                ['label' => __('Maxi format'), 'value' => '2'],
                                ['label' => __('Solid'), 'value' => '3'],
                                ['label' => __('Solide'), 'value' => '4'],
                                ['label' => __('Kit'), 'value' => '5'],
                                ['label' => __('Composition et coffret'), 'value' => '6'],
                                ['label' => __('Concentrated'), 'value' => '7'],
                                ['label' => __('Concentré'), 'value' => '8']
                            ];
        }

        return $this->_options;
    }
}