<?php

namespace Albatool\SkinQuizConsultant\Model;

class StoreList
{
    const ENGLISH_SITE = 'en';
    const ARABIC_SITE = 'ar';
    const BOTH_SITE = 'both';
    const DEFAULT_RESPONSE = 'default';

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::BOTH_SITE => __('Both Sites'), 
            self::ENGLISH_SITE => __('English'),
            self::ARABIC_SITE => __('Arabic'),
            self::DEFAULT_RESPONSE => __('Default Response')
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