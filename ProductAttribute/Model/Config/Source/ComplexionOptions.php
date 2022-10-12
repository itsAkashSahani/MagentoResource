<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class ComplexionOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Rosy'), 'value' => '1'],
                                ['label' => __('Beige'), 'value' => '2'],
                                ['label' => __('Golden'), 'value' => '3'],
                                ['label' => __('Brown'), 'value' => '4'],
                                ['label' => __('Rosé'), 'value' => '5'],
                                ['label' => __('Doré'), 'value' => '6']
                            ];
        }

        return $this->_options;
    }
}