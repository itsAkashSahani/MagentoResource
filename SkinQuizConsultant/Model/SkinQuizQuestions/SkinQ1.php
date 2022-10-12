<?php

namespace Albatool\SkinQuizConsultant\Model\SkinQuizQuestions;

class SkinQ1
{
    /**#@+
     * Status values
     */
    const SKIN_Q1_O1 = 'NORMAL';
    const SKIN_Q1_O2 = 'MIXTE';
    const SKIN_Q1_O3 = 'OILY';
    const SKIN_Q1_O4 = 'DRY';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::SKIN_Q1_O1 => __('NORMAL'), 
            self::SKIN_Q1_O2 => __('MIXTE'),
            self::SKIN_Q1_O3 => __('OILY'),
            self::SKIN_Q1_O3 => __('DRY')
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