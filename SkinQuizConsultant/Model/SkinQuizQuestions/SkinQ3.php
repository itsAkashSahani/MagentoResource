<?php

namespace Albatool\SkinQuizConsultant\Model\SkinQuizQuestions;

class SkinQ3
{
    /**#@+
     * Status values
     */
    const SKIN_Q3_O1 = 'EXPRESS';
    const SKIN_Q3_O2 = 'CLASSIC';
    const SKIN_Q3_O3 = 'EXPERT';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::SKIN_Q3_O1 => __('EXPRESS'), 
            self::SKIN_Q3_O2 => __('CLASSIC'),
            self::SKIN_Q3_O3 => __('Exposed to pollution')
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