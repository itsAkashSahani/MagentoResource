<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class IntensityOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Light'), 'value' => '1'],
                                ['label' => __('Medium'), 'value' => '2'],
                                ['label' => __('Intense'), 'value' => '3'],
                                ['label' => __('Légère'), 'value' => '4']
                            ];
        }

        return $this->_options;
    }
}