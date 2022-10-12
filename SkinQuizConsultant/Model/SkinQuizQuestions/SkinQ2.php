<?php

namespace Albatool\SkinQuizConsultant\Model\SkinQuizQuestions;

class SkinQ2
{
    /**#@+
     * Status values
     */
    const SKIN_Q2_O1 = 'No specific';
    const SKIN_Q2_O2 = 'Oily';
    const SKIN_Q2_O3 = 'Dullness';
    const SKIN_Q2_O4 = 'Imperfections';
    const SKIN_Q2_O5 = 'Sensitive';
    const SKIN_Q2_O6 = 'Aging';
    const SKIN_Q2_O7 = 'Weakness';
    const SKIN_Q2_O8 = 'Dark spots';
    const SKIN_Q2_O9 = 'Tired skin';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::SKIN_Q2_O1 => __('No specific'), 
            self::SKIN_Q2_O2 => __('Oily'),
            self::SKIN_Q2_O3 => __('Dullness'),
            self::SKIN_Q2_O4 => __('Imperfections'),
            self::SKIN_Q2_O5 => __('Sensitive'),
            self::SKIN_Q2_O6 => __('Aging'),
            self::SKIN_Q2_O7 => __('Weakness'),
            self::SKIN_Q2_O8 => __('Dark spots'),
            self::SKIN_Q2_O9 => __('Tired skin')
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