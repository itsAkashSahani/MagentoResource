<?php
namespace Albatool\StoreLocator\Block;

use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
 
    /**
     * @var SourceRepositoryInterface
     */
    private $sourceRepository;

    private $store;
 
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SourceRepositoryInterface $sourceRepository,
        \Magento\Framework\Locale\Resolver $store,
        \Magento\Cms\Model\Template\FilterProvider $contentProcessor
    ) {
        parent::__construct($context);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sourceRepository = $sourceRepository;
        $this->store = $store;
        $this->contentProcessor = $contentProcessor;
    }
 
    /**
     * Get source details
     *
     * @return SourceInterface[]|null
     */
    public function getSourcesDetails($source_code)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('source_code', $source_code)->create();
        $sourceInfo = null;
        try {
            $sourceData = $this->sourceRepository->getList($searchCriteria);
            if ($sourceData->getTotalCount()) {
                $sourceInfo = $sourceData->getItems();
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
 
        return $sourceInfo;
    }

    public function getStoreLang() {
        $currentStore = $this->store->getLocale();

        if($currentStore == 'en_US') {
            return 'en';
        }

        if($currentStore == 'ar_SA') {
            return 'ar';
        }

    }

    public function getOpeningTiming($storeInfo) {
        $storeTime = [];
        $none = 'none';
        $close = 'close';
        foreach($storeInfo as $item) {

            if($item->getMondayField() != null) {
                if($item->getMondayField() == 'close') {
                    array_push($storeTime, $close);
                }
                else {
                    $monday = explode(",",$item->getMondayField());
                    array_push($storeTime, $monday);
                }
            }
            else {
                array_push($storeTime, $none);
            }

            if($item->getTuesdayField() != null) {
                if($item->getTuesdayField() == 'close') {
                    array_push($storeTime, $close);
                }
                else {
                    $tuesday = explode(",",$item->getTuesdayField());
                    array_push($storeTime, $tuesday);
                }
            }
            else {
                array_push($storeTime, $none);
            }

            if($item->getWednesdayField() != null) {
                if($item->getWednesdayField() == 'close') {
                    array_push($storeTime, $close);
                }
                else {
                    $wednesday = explode(",",$item->getWednesdayField());
                    array_push($storeTime, $wednesday);
                }
            }
            else {
                array_push($storeTime, $none);
            }

            if($item->getThursdayField() != null) {
                if($item->getThursdayField() == 'close') {
                    array_push($storeTime, $close);
                }
                else {
                    $thursday = explode(",",$item->getThursdayField());
                    array_push($storeTime, $thursday);
                }
            }
            else {
                array_push($storeTime, $none);
            }

            if($item->getFridayField() != null) {
                if($item->getFridayField() == 'close') {
                    array_push($storeTime, $close);
                }
                else {
                    $friday = explode(",",$item->getFridayField());
                    array_push($storeTime, $friday);
                }
            }
            else {
                array_push($storeTime, $none);
            }

            if($item->getSaturdayField() != null) {
                if($item->getSaturdayField() == 'close') {
                    array_push($storeTime, $close);
                }
                else {
                    $saturday = explode(",",$item->getSaturdayField());
                    array_push($storeTime, $saturday);
                }
            }
            else {
                array_push($storeTime, $none);
            }

            if($item->getSundayField() != null) {
                if($item->getSundayField() == 'close') {
                    array_push($storeTime, $close);
                }
                else {
                    $sunday = explode(",",$item->getSundayField());
                    array_push($storeTime, $sunday);
                }
            }
            else {
                array_push($storeTime, $none);
            }
            
            $storeTime['storeName'] = $item->getName();
        }
        return $storeTime;
    }

    public function getStoreBanner($storeInfo) {
        foreach($storeInfo as $item) {
            $storeBanner = $this->contentProcessor->getPageFilter()->filter($item->getFrontendDescription());
        }
        return $storeBanner;
    }
}