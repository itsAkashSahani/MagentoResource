<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class ActionsOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Nettoyer'), 'value' => '1'],
                                ['label' => __('Démaquiller'), 'value' => '2'],
                                ['label' => __('Gommer et exfolier'), 'value' => '3'],
                                ['label' => __('Hydrater'), 'value' => '4'],
                                ['label' => __('Apaiser'), 'value' => '5'],
                                ['label' => __('Raffermir et lifter'), 'value' => '6'],
                                ['label' => __('Régénérer'), 'value' => '7'],
                                ['label' => __('Nourrir et réparer'), 'value' => '8'],
                                ['label' => __('Purifier et matifier'), 'value' => '9'],
                                ['label' => __('Lisser et défroisser'), 'value' => '10'],
                                ['label' => __('Donner de l’éclat'), 'value' => '11'],
                                ['label' => __('Anti-Age'), 'value' => '12'],
                                ['label' => __('Silhouette & minceur'), 'value' => '13'],
                                ['label' => __('Nourrir'), 'value' => '14'],
                                ['label' => __('Réparer'), 'value' => '15'],
                                ['label' => __('Anti-Chute et Anti-pelliculaire'), 'value' => '16'],
                                ['label' => __('Réparation'), 'value' => '17'],
                                ['label' => __('Brillance'), 'value' => '18'],
                                ['label' => __('Volume et densité'), 'value' => '19'],
                                ['label' => __('Définition des boucles'), 'value' => '20'],
                                ['label' => __('Détox et Pureté'), 'value' => '21'],
                                ['label' => __('Définition des boucles'), 'value' => '22'],
                                ['label' => __('Booster des reflets'), 'value' => '23'],
                                ['label' => __('Protection de la couleur'), 'value' => '24'],
                                ['label' => __('Lissage et Discipline'), 'value' => '25'],
                                ['label' => __('Nutrition'), 'value' => '26'],
                                ['label' => __('Stimuler le cuir chevelu'), 'value' => '27'],
                                ['label' => __('Minceur'), 'value' => '28'],
                                ['label' => __('Beauté'), 'value' => '29'],
                                ['label' => __('Bien-être'), 'value' => '30']
                            ];
        }

        return $this->_options;
    }
}