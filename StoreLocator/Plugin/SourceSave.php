<?php
namespace Albatool\StoreLocator\Plugin;

use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\Data\SourceSearchResultsInterface;
use Magento\InventoryApi\Api\Data\SourceExtensionFactory;
use Magento\InventoryApi\Api\Data\SourceExtensionInterfaceFactory;
use Magento\InventoryApi\Api\Data\StockInterfaceFactory;


class SourceSave
{

    const MONDAY_FIELD = 'monday_field';
    const TUESDAY_FIELD = 'tuesday_field';
    const WEDNESDAY_FIELD = 'wednesday_field';
    const THURSDAY_FIELD = 'thursday_field';
    const FRIDAY_FIELD = 'friday_field';
    const SATURDAY_FIELD = 'saturday_field';
    const SUNDAY_FIELD = 'sunday_field';

    protected $extensionFactory;
    protected $sourceFactory;

    public function __construct(SourceExtensionFactory $extensionFactory)
    {
        $this->extensionFactory = $extensionFactory;
    }


    public function afterGet(SourceRepositoryInterface $subject, SourceInterface $source)
    {
        $sourceMonday = $source->getData(self::MONDAY_FIELD);
        $sourceTuesday = $source->getData(self::TUESDAY_FIELD);
        $sourceWednesday = $source->getData(self::WEDNESDAY_FIELD);
        $sourceThursday = $source->getData(self::THURSDAY_FIELD);
        $sourceFriday = $source->getData(self::FRIDAY_FIELD);
        $sourceSaturday = $source->getData(self::SATURDAY_FIELD);
        $sourceSunday = $source->getData(self::SUNDAY_FIELD);

        $extensionAttributes = $source->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
        $extensionAttributes->setMondayField($sourceMonday);
        $extensionAttributes->setTuesdayField($sourceTuesday);
        $extensionAttributes->setWednesdayField($sourceWednesday);
        $extensionAttributes->setThursdayField($sourceThursday);
        $extensionAttributes->setFridayField($sourceFriday);
        $extensionAttributes->setSaturdayField($sourceSaturday);
        $extensionAttributes->setSundayField($sourceSunday);
        $source->setExtensionAttributes($extensionAttributes);

        return $source;
    }

    public function afterGetList(SourceRepositoryInterface $subject, SourceSearchResultsInterface $result)
    {
        $products = [];
        $sources = $result->getItems();

        foreach ($sources as $source) {
            $sourceMonday = $source->getData(self::MONDAY_FIELD);
            $sourceTuesday = $source->getData(self::TUESDAY_FIELD);
            $sourceWednesday = $source->getData(self::WEDNESDAY_FIELD);
            $sourceThursday = $source->getData(self::THURSDAY_FIELD);
            $sourceFriday = $source->getData(self::FRIDAY_FIELD);
            $sourceSaturday = $source->getData(self::SATURDAY_FIELD);
            $sourceSunday = $source->getData(self::SUNDAY_FIELD);


            $extensionAttributes = $source->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setMondayField($sourceMonday);
            $extensionAttributes->setTuesdayField($sourceTuesday);
            $extensionAttributes->setWednesdayField($sourceWednesday);
            $extensionAttributes->setThursdayField($sourceThursday);
            $extensionAttributes->setFridayField($sourceFriday);
            $extensionAttributes->setSaturdayField($sourceSaturday);
            $extensionAttributes->setSundayField($sourceSunday);


            $source->setExtensionAttributes($extensionAttributes);
            $products[] = $source;
        }
        $result->setItems($products);
        return $result;
    }

    public function beforeSave(
        SourceRepositoryInterface $subject,
        SourceInterface $source
    )
    {
        $extensionAttributes = $source->getExtensionAttributes() ?: $this->extensionFactory->create();
        if ($extensionAttributes !== null &&
            $extensionAttributes->getMondayField() !== null &&
            $extensionAttributes->getTuesdayField() !== null &&
            $extensionAttributes->getWednesdayField() !== null &&
            $extensionAttributes->getThursdayField() !== null &&
            $extensionAttributes->getFridayField() !== null &&
            $extensionAttributes->getSaturdayField() !== null &&
            $extensionAttributes->getSundayField() !== null
            ) {
            $source->setMondayField($extensionAttributes->getMondayField());
            $source->setTuesdayField($extensionAttributes->getTuesdayField());
            $source->setWednesdayField($extensionAttributes->getWednesdayField());
            $source->setThursdayField($extensionAttributes->getThursdayField());
            $source->setFridayField($extensionAttributes->getFridayField());
            $source->setSaturdayField($extensionAttributes->getSaturdayField());
            $source->setSundayField($extensionAttributes->getSundayField());
        }

        // foreach($source->getData() as $a) {
        //     print_r($s->getData());
        // }

        // print_r($source->getData());
        // die;
        return [$source];
    }

}