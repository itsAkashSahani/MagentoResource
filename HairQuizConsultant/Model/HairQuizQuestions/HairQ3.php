<?php

namespace Albatool\HairQuizConsultant\Model\HairQuizQuestions;

class HairQ3
{
    /**#@+
     * Status values
     */
    const HAIR_Q3_O1 = 'Normal';
    const HAIR_Q3_O2 = 'With dandruff';
    const HAIR_Q3_O3 = 'Exposed to pollution';
    const HAIR_Q3_O4 = 'Oily';
    const HAIR_Q3_O5 = 'Dry & sensitive';
    const HAIR_Q3_O6 = 'With a hair loss';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::HAIR_Q3_O1 => __('Normal'), 
            self::HAIR_Q3_O2 => __('With dandruff'),
            self::HAIR_Q3_O3 => __('Exposed to pollution'),
            self::HAIR_Q3_O4 => __('Oily'), 
            self::HAIR_Q3_O5 => __('Dry & sensitive'),
            self::HAIR_Q3_O6 => __('With a hair loss')
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
}