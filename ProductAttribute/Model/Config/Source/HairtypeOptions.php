<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class HairtypeOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Dandruff hair'), 'value' => '1'],
                                ['label' => __('Dull hair'), 'value' => '2'],
                                ['label' => __('All hair types'), 'value' => '3'],
                                ['label' => __('Dry Hair'), 'value' => '4'],
                                ['label' => __('Greasy Hair'), 'value' => '5'],
                                ['label' => __('Fine Hair'), 'value' => '6'],
                                ['label' => __('Numb Hair Loss'), 'value' => '7'],
                                ['label' => __('Colored Hair'), 'value' => '8'],
                                ['label' => __('Unruly Hair'), 'value' => '9'],
                                ['label' => __('Extra Dry Damaged Hair'), 'value' => '10'],
                                ['label' => __('Curly Hair'), 'value' => '11'],
                                ['label' => __('Cheveux à pellicules'), 'value' => '12'],
                                ['label' => __('Cheveux ternes'), 'value' => '13'],
                                ['label' => __('Tous types de cheveux'), 'value' => '14'],
                                ['label' => __('Cheveux secs'), 'value' => '15'],
                                ['label' => __('Cheveux gras'), 'value' => '16'],
                                ['label' => __('Cheveux fins et plats'), 'value' => '17'],
                                ['label' => __('Cheveux dévitalisés, chute de cheveux'), 'value' => '18'],
                                ['label' => __('Cheveux colorés ou méchés'), 'value' => '19'],
                                ['label' => __('Cheveux indisciplinés'), 'value' => '20'],
                                ['label' => __('Cheveux très secs ou abîmés'), 'value' => '21'],
                                ['label' => __('Cheveux bouclés'), 'value' => '22']
                            ];
        }

        return $this->_options;
    }
}