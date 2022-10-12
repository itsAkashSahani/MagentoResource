<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class NoteOptions extends AbstractSource
{
    
    public function getAllOptions()
    {
        if (null === $this->_options) {
            
                $this->_options=[
                                ['label' => __('Colorful Freshness'), 'value' => '1'],
                                ['label' => __('Timeless Floral'), 'value' => '2'],
                                ['label' => __('Sensuality'), 'value' => '3'],
                                ['label' => __('Woody'), 'value' => '4'],
                                ['label' => __('Fraîcheur Colorée'), 'value' => '5'],
                                ['label' => __('Floral Intemporel'), 'value' => '6'],
                                ['label' => __('Sensualité'), 'value' => '7'],
                                ['label' => __('Caractère Boisé'), 'value' => '8']
                            ];
        }

        return $this->_options;
    }
}