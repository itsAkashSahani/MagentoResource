<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class EffectOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Shiny - Glossy'), 'value' => '1'],
                                ['label' => __('Matt'), 'value' => '2'],
                                ['label' => __('Pearly_Glittery'), 'value' => '3'],
                                ['label' => __('Satiny'), 'value' => '4'],
                                ['label' => __('Long lasting'), 'value' => '5'],
                                ['label' => __('High Coverage'), 'value' => '6'],
                                ['label' => __('Luminous'), 'value' => '7'],
                                ['label' => __('Brillant - Glossy'), 'value' => '8'],
                                ['label' => __('Mat'), 'value' => '9'],
                                ['label' => __('Nacré - Pailleté'), 'value' => '10'],
                                ['label' => __('Satiné'), 'value' => '11'],
                                ['label' => __('Longue Tenue'), 'value' => '12'],
                                ['label' => __('Haute Couvrance'), 'value' => '13'],
                                ['label' => __('Lumineux'), 'value' => '14']
                            ];
        }

        return $this->_options;
    }
}