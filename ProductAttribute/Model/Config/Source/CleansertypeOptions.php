<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class CleansertypeOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Micellar water'), 'value' => '1'],
                                ['label' => __('Oil cleanser'), 'value' => '2'],
                                ['label' => __('Milk cleanser'), 'value' => '3'],
                                ['label' => __('Gel cleanser'), 'value' => '4'],
                                ['label' => __('Floral water'), 'value' => '5'],
                                ['label' => __('Makeup wipes'), 'value' => '6'],
                                ['label' => __('Bi phase makeup remover'), 'value' => '7'],
                                ['label' => __('Eau micellaire'), 'value' => '8'],
                                ['label' => __('Huile démaquillante'), 'value' => '9'],
                                ['label' => __('Lait démaquillant'), 'value' => '10'],
                                ['label' => __('Gel nettoyant'), 'value' => '11'],
                                ['label' => __('Eau florale'), 'value' => '12'],
                                ['label' => __('Lingette démaquillante'), 'value' => '13'],
                                ['label' => __('Démaquillant bi-phase'), 'value' => '14']
                            ];
        }

        return $this->_options;
    }
}