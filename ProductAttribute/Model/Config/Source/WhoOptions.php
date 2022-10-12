<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class WhoOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('For her'), 'value' => '1'],
                                ['label' => __('For him'), 'value' => '2'],
                                ['label' => __('Pour elle'), 'value' => '3'],
                                ['label' => __('Pour lui'), 'value' => '4']
                            ];
        }

        return $this->_options;
    }
}