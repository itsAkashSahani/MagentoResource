<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class EngagementOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Sans Sulfate'), 'value' => '1'],
                                ['label' => __('Sans Silicone'), 'value' => '2'],
                                ['label' => __('Sans Parfum'), 'value' => '3'],
                                ['label' => __('Produit Vegan'), 'value' => '4'],
                                ['label' => __('Plant for Life'), 'value' => '5'],
                                ['label' => __('Les super engagés'), 'value' => '6']
                            ];
        }

        return $this->_options;
    }
}