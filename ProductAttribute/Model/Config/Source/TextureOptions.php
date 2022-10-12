<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class TextureOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Balm'), 'value' => '1'],
                                ['label' => __('Cream'), 'value' => '2'],
                                ['label' => __('Gel jelly'), 'value' => '3'],
                                ['label' => __('Oil'), 'value' => '4'],
                                ['label' => __('Milk'), 'value' => '5'],
                                ['label' => __('Mist, Lotion & Water'), 'value' => '6'],
                                ['label' => __('Fluid'), 'value' => '7'],
                                ['label' => __('Scrub'), 'value' => '8'],
                                ['label' => __('Baume'), 'value' => '9'],
                                ['label' => __('Crème'), 'value' => '10'],
                                ['label' => __('Gel et gelée'), 'value' => '11'],
                                ['label' => __('Huile'), 'value' => '12'],
                                ['label' => __('Lait'), 'value' => '13'],
                                ['label' => __('Brume, eau et lotion'), 'value' => '14'],
                                ['label' => __('Légère'), 'value' => '15'],
                                ['label' => __('Fluide'), 'value' => '16'],
                                ['label' => __('Gommage'), 'value' => '17'],
                                ['label' => __('Gel'), 'value' => '18']
                            ];
        }

        return $this->_options;
    }
}