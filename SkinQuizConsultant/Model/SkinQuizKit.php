<?php

namespace Albatool\SkinQuizConsultant\Model;
use Mirasvit\ProductKit\Model\ResourceModel\Kit\CollectionFactory as KitCollection;

class SkinQuizKit
{
    protected $kitCollection;

    public function __construct(
        KitCollection $kitCollection
    ) {
        $this->kitCollection = $kitCollection;
    }

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public function getOptionArray()
    {
        $collection = $this->kitCollection->create();
        $collection->addFieldToFilter('is_active', ['eq' => '1']);
        $options = [];
        $options[] = ['label' => __("Select Bundle"),'value' => ''];
        foreach($collection as $kit) {
            $options[] = ['label' => $kit->getName(),'value' => (int)$kit->getKitId()];
        }

        return $options;
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