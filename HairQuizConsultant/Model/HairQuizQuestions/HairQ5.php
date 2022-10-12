<?php

namespace Albatool\HairQuizConsultant\Model\HairQuizQuestions;

class HairQ5
{
    /**#@+
     * Status values
     */
    const HAIR_Q5_O1 = 'Express';
    const HAIR_Q5_O2 = 'Expert';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::HAIR_Q5_O1 => __('Express'), 
            self::HAIR_Q5_O2 => __('Expert')
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