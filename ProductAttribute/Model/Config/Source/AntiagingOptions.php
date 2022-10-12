<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class AntiagingOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Anti-wrinkle'), 'value' => '1'],
                                ['label' => __('Anti dark spots'), 'value' => '2'],
                                ['label' => __('First wrinkles'), 'value' => '3'],
                                ['label' => __('Lifting'), 'value' => '4'],
                                ['label' => __('Anti-rides'), 'value' => '5'],
                                ['label' => __('Anti-tâches'), 'value' => '6'],
                                ['label' => __('Premières rides'), 'value' => '7'],
                                ['label' => __('Effet liftant'), 'value' => '8']
                            ];
        }

        return $this->_options;
    }
}