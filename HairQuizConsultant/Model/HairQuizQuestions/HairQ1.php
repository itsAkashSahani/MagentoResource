<?php

namespace Albatool\HairQuizConsultant\Model\HairQuizQuestions;

class HairQ1
{
    /**#@+
     * Status values
     */
    const HAIR_Q1_O1 = 'Short';
    const HAIR_Q1_O2 = 'Medium';
    const HAIR_Q1_O3 = 'Long';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::HAIR_Q1_O1 => __('Short'), 
            self::HAIR_Q1_O2 => __('Medium'),
            self::HAIR_Q1_O3 => __('Long')
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