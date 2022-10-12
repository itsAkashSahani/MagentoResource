<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class CoverageOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Light'), 'value' => '1'],
                                ['label' => __('Medium'), 'value' => '2'],
                                ['label' => __('High'), 'value' => '3'],
                                ['label' => __('Légère'), 'value' => '4'],
                                ['label' => __('Haute'), 'value' => '5']
                            ];
        }

        return $this->_options;
    }
}