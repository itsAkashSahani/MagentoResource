<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class NeedOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Cleanse'), 'value' => '1'],
                                ['label' => __('Nettoyer'), 'value' => '2'],
                                ['label' => __('Makeup remover'), 'value' => '3'],
                                ['label' => __('Démaquiller'), 'value' => '4'],
                                ['label' => __('Exfoliate'), 'value' => '5'],
                                ['label' => __('Gommer et exfolier'), 'value' => '6'],
                                ['label' => __('Hydrate'), 'value' => '7'],
                                ['label' => __('Hydrater'), 'value' => '8'],
                                ['label' => __('Soothe'), 'value' => '9'],
                                ['label' => __('Apaiser'), 'value' => '10'],
                                ['label' => __('Firm and lift'), 'value' => '11'],
                                ['label' => __('Raffermir et lifter'), 'value' => '12'],
                                ['label' => __('Regenerate'), 'value' => '13'],
                                ['label' => __('Régénérer'), 'value' => '14'],
                                ['label' => __('Nourish & Repair'), 'value' => '15'],
                                ['label' => __('Nourrir et réparer'), 'value' => '16'],
                                ['label' => __('Purify & Matify'), 'value' => '17'],
                                ['label' => __('Purifier et matifier'), 'value' => '18'],
                                ['label' => __('Fill in'), 'value' => '19'],
                                ['label' => __('Lisser et défroisser'), 'value' => '20'],
                                ['label' => __('Illuminate'), 'value' => '21'],
                                ['label' => __('Donner de l’éclat'), 'value' => '22'],
                                ['label' => __('Anti-aging'), 'value' => '23'],
                                ['label' => __('Anti-Age'), 'value' => '24'],
                                ['label' => __('Anti-dark circles and anti-puffiness'), 'value' => '25'],
                                ['label' => __('Anti-cernes et anti-poches'), 'value' => '26'],
                                ['label' => __('Anti-pollution and blue light protection'), 'value' => '27'],
                                ['label' => __('Anti-pollution et protection lumière bleue'), 'value' => '28'],
                                ['label' => __('Silhouette'), 'value' => '29'],
                                ['label' => __('Nourrir'), 'value' => '30'],
                                ['label' => __('Repair'), 'value' => '31'],
                                ['label' => __('Silhouette & minceur'), 'value' => '32'],
                                ['label' => __('Réparer'), 'value' => '33'],
                                ['label' => __('Sun protection'), 'value' => '34'],
                                ['label' => __('Protéger du soleil'), 'value' => '35'],
                                ['label' => __('Anti Hair loss & Anti dandruff'), 'value' => '36'],
                                ['label' => __('Repairing'), 'value' => '37'],
                                ['label' => __('Shine enhancing'), 'value' => '38'],
                                ['label' => __('Volumizing'), 'value' => '39'],
                                ['label' => __('Curl enhancement'), 'value' => '40'],
                                ['label' => __('Purifying and detoxifying'), 'value' => '41'],
                                ['label' => __('Highlights boosting'), 'value' => '42'],
                                ['label' => __('Color Protecting'), 'value' => '43'],
                                ['label' => __('Straightening  and frizz control'), 'value' => '44'],
                                ['label' => __('Nourishing'), 'value' => '45'],
                                ['label' => __('Scalp stimulating'), 'value' => '46'],
                                ['label' => __('Anti-Chute et Anti-pelliculaire'), 'value' => '47'],
                                ['label' => __('Réparation'), 'value' => '48'],
                                ['label' => __('Brillance'), 'value' => '49'],
                                ['label' => __('Volume et densité'), 'value' => '50'],
                                ['label' => __('Définition des boucles'), 'value' => '51'],
                                ['label' => __('Détox et Pureté'), 'value' => '52'],
                                ['label' => __('Booster des reflets'), 'value' => '53'],
                                ['label' => __('Protection de la couleur'), 'value' => '54'],
                                ['label' => __('Lissage et Discipline'), 'value' => '55'],
                                ['label' => __('Nutrition'), 'value' => '56'],
                                ['label' => __('Stimuler le cuir chevelu'), 'value' => '57'],
                                ['label' => __('Slimming'), 'value' => '58'],
                                ['label' => __('Beauty'), 'value' => '59'],
                                ['label' => __('Wellness'), 'value' => '60'],
                                ['label' => __('Minceur'), 'value' => '61'],
                                ['label' => __('Beauté'), 'value' => '62'],
                                ['label' => __('Bien-être'), 'value' => '63']
                            ];
        }

        return $this->_options;
    }
}