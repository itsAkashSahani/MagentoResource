<?php
namespace Albatool\StoreLocator\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface ;

class SaveStoreTime implements ObserverInterface
{
    protected $sourceRepository;

    public function __construct(
        SourceRepositoryInterface $sourceRepository
    ){
        $this->sourceRepository = $sourceRepository;
    }
    
    
    
    
    /**
     * Below is the method that will fire whenever the event runs!
     *
     * @param Observer $observer
     */

    public function execute(Observer $observer)
    {
        $request = $observer->getEvent()->getRequest();
        $requestData = $request->getParam('general', []);

        $monday =  $requestData['extension_attributes']['monday_field'];
        $tuesday =  $requestData['extension_attributes']['tuesday_field'];
        $wednesday =  $requestData['extension_attributes']['wednesday_field'];
        $thursday =  $requestData['extension_attributes']['thursday_field'];
        $friday =  $requestData['extension_attributes']['friday_field'];
        $saturday =  $requestData['extension_attributes']['saturday_field'];
        $sunday =  $requestData['extension_attributes']['sunday_field'];
        $sourceCode = $requestData['source_code'];

        // print_r($type);
        // exit;
        //Magento\InventoryApi\Api\SourceRepositoryInterface 
        $source = $this->sourceRepository->get($sourceCode);
        $source->setMondayField($monday);
        $source->setTuesdayField($tuesday);
        $source->setWednesdayField($wednesday);
        $source->setThursdayField($thursday);
        $source->setFridayField($friday);
        $source->setSaturdayField($saturday);
        $source->setSundayField($sunday);
        $source->save();
    }
}