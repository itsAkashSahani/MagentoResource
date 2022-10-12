<?php

namespace Albatool\StoreLocator\Model;

use Magento\Inventory\Model\SourceFactory as SourceFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class GetStoreLocations implements \Albatool\StoreLocator\Api\StoreLocatorInterface
{
	
	/**
     * @var SourceFactory
     */
	protected $sourceFactory;
	
	/**
     * @var resultJsonFactory
     */
	protected $resultJsonFactory;
	
	/**
     * @var Magento\Framework\App\RequestInterface
     */
	protected $request;

	/**
     * @var Magento\InventoryApi\Api\GetSourceItemsBySkuInterface
     */
	protected $sourceItemsBySku;
	
	 /**
     * @param SourceFactory $sourceFactory
	 * @param JsonFactory $resultJsonFactory
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @param \Magento\InventoryApi\Api\GetSourceItemsBySkuInterface $sourceItemsBySku
     */
	public function __construct(
		SourceFactory $sourceFactory,
		JsonFactory $resultJsonFactory,
	   \Magento\Framework\App\RequestInterface $request,
	   \Magento\InventoryApi\Api\GetSourceItemsBySkuInterface $sourceItemsBySku
    ) {
		$this->sourceFactory = $sourceFactory;
		$this->resultJsonFactory   = $resultJsonFactory;
		$this->sourceItemsBySku = $sourceItemsBySku;
	    $this->request = $request;
    }
	
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
		$sourceModel = $this->sourceFactory->create();
		$cityName = $this->request->getParam('city');
		$code = $this->request->getParam('source_code');

		if(isset($cityName)) {
			$sourceCollection = $sourceModel->getCollection()
											->addFieldToFilter('city', ['eq' => $cityName])
											->addFieldToFilter('source_code', ['neq' => 'default'])
											->addFieldToFilter('enabled', ['eq' => 1]);
		}
		elseif(isset($code)) {
			$sourceCollection = $sourceModel->getCollection()
											->addFieldToFilter('source_code', ['eq' => $code, 'neq' => 'default'])
											->addFieldToFilter('enabled', ['eq' => 1]);
		}
		else {
			$sourceCollection = $sourceModel->getCollection()
											->addFieldToFilter('source_code', ['neq' => 'default'])
											->addFieldToFilter('enabled', ['eq' => 1]);

		}
	
		$cityData['items'] = [];
		$count = 0;
		foreach($sourceCollection as $item) {
				$cityData['items'][$count] = $item->getData();
				$count++;
		}
		return json_encode($cityData);
		// return $cityName;
    }
	
	public function getcity(){

		$sourceModel = $this->sourceFactory->create();
		$cityCollection = $sourceModel->getCollection()
									->addFieldToSelect('city')
									->addFieldToFilter('source_code', ['neq' => 'default'])
									->addFieldToFilter('enabled', ['eq' => 1])
									->setOrder('city', 'ASC')
									->distinct(true);
		$cityData['cities'] = [];
		$count = 0;
		foreach($cityCollection as $city) {
			$cityData['cities'][$count] = $city->getCity();
			$count++;
		}

		return json_encode($cityData);
	}

	public function getLocationOfStore() {

		$resultJson = $this->resultJsonFactory->create();

		$sourceModel = $this->sourceFactory->create();
		$cityCollection = $sourceModel->getCollection()
									->addFieldToFilter('enabled', ['eq' => 1])
									->addFieldToFilter('source_code', ['neq' => 'default'])
									->addFieldToSelect('latitude')
									->addFieldToSelect('longitude');

		$cityData = [];
		$count = 0;
		foreach($cityCollection as $store) {
			$cityData[$count]['lat'] = $store->getLatitude();
			$cityData[$count]['lng'] = $store->getLongitude();
			$count++;
		}
		
		return json_encode($cityData);
	}

	public function getStoresInCity() {
		return true;
	}

}