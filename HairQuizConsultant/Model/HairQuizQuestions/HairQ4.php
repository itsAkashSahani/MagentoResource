<?php

namespace Albatool\HairQuizConsultant\Model\HairQuizQuestions;

class HairQ4
{
    /**#@+
     * Status values
     */
    const HAIR_Q4_O1 = 'Damaged and brittle hair';
    const HAIR_Q4_O2 = 'Dyed hair';
    const HAIR_Q4_O3 = 'Exposed to pollution';
    const HAIR_Q4_O4 = 'Frizzy hair';
    const HAIR_Q4_O5 = 'Normal hair';
    const HAIR_Q4_O6 = 'Dull hair';
    const HAIR_Q4_O7 = 'Fine hair';
    const HAIR_Q4_O8 = 'Curly hair';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::HAIR_Q4_O1 => __('Damaged & brittle hair with split-ends'), 
            self::HAIR_Q4_O2 => __('Colour treated hair'),
            self::HAIR_Q4_O3 => __('Hair exposed to pollution'),
            self::HAIR_Q4_O4 => __('Indisciplined hair'), 
            self::HAIR_Q4_O5 => __('Normal hair'),
            self::HAIR_Q4_O6 => __('Dull hair'),
            self::HAIR_Q4_O7 => __('Fine hair'), 
            self::HAIR_Q4_O8 => __('Curly hair')
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