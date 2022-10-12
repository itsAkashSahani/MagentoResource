<?php

namespace Albatool\HairQuizConsultant\Model\HairQuizQuestions;

class HairQ2
{
    /**#@+
     * Status values
     */
    const HAIR_Q2_O1 = 'Fine';
    const HAIR_Q2_O2 = 'Normal';
    const HAIR_Q2_O3 = 'Thick';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::HAIR_Q2_O1 => __('Fine'), 
            self::HAIR_Q2_O2 => __('Normal'),
            self::HAIR_Q2_O3 => __('Thick')
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