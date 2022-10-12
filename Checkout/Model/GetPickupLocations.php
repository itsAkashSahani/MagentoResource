<?php

namespace Albatool\Checkout\Model;

use Magento\Inventory\Model\SourceFactory as SourceFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class GetPickupLocations implements \Albatool\Checkout\Api\PickupLocationsInterface
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
		$searchRequest = $this->request->getParam('searchRequest');
		$storeData = array();
		$sourcedata = array();
		$count = 0 ;
		$totalSku = 0;
		if(isset($searchRequest['extensionAttributes']['productsInfo'])){
			foreach($searchRequest['extensionAttributes']['productsInfo'] as $product){
				$sourceItemList = $this->sourceItemsBySku->execute($product['sku']);
				foreach ($sourceItemList as $source) {
					$sourcedata[$count] = $source->getData();
					$count++;
				}
				$totalSku++;
			}
			$sourceModel = $this->sourceFactory->create();

			$sortSourcedata = array();
			foreach ($sourcedata as $key => $row)
			{
				$sortSourcedata[$key] = $row['quantity'];
			}
			array_multisort($sortSourcedata, SORT_DESC, $sourcedata);
			foreach ($sourcedata as $key => $store)
			{

				if(isset($searchRequest['area'])){
					$searchTerm = explode(":",$searchRequest['area']['searchTerm']);
					if(is_numeric($searchTerm[0])){
						if($store['source_code'] != 'default'){
							if (array_key_exists($store['source_code'], $storeData)) {
								$storeData[$store['source_code']]['sku'] = $storeData[$store['source_code']]['sku'].','.$store['sku'];
								$skuArray = explode(',',$storeData[$store['source_code']]['sku']);
								$storeData[$store['source_code']]['total_product_not_found'] = $totalSku - count($skuArray);
							}else{
								$sourceModalData = $sourceModel->getCollection()
													->addFieldToFilter('source_code',array('eq'=>$store['source_code']))
													->addFieldToFilter('country_id',array('eq'=>$searchTerm[1]))
													->addFieldToFilter('postcode',array('eq' =>$searchTerm[0]))
													->getFirstItem()
													->getData();
								if(!empty($sourceModalData)){
									$sourceModalData['source_item_id'] = $store['source_item_id'];
									$sourceModalData['sku'] = $store['sku'];
									$sourceModalData['quantity'] = $store['quantity'];
									$sourceModalData['status'] = $store['status'];
									$storeData[$store['source_code']] = $sourceModalData;
									$storeData[$store['source_code']]['total_product_not_found'] = $totalSku - 1;
								}
							}
						}
					}else{
						if($store['source_code'] != 'default'){
							if (array_key_exists($store['source_code'], $storeData)) {
								$storeData[$store['source_code']]['sku'] = $storeData[$store['source_code']]['sku'].','.$store['sku'];
								$skuArray = explode(',',$storeData[$store['source_code']]['sku']);
								$storeData[$store['source_code']]['total_product_not_found'] = $totalSku - count($skuArray);
							}else{
								$sourceModalData = $sourceModel->getCollection()
													->addFieldToFilter('source_code',array('eq'=>$store['source_code']))
													->addFieldToFilter('country_id',array('eq'=>$searchTerm[1]))
													->addFieldToFilter('city',array('eq' =>$searchTerm[0]))
													->getFirstItem()
													->getData();
								if(!empty($sourceModalData)){
									$sourceModalData['source_item_id'] = $store['source_item_id'];
									$sourceModalData['sku'] = $store['sku'];
									$sourceModalData['quantity'] = $store['quantity'];
									$sourceModalData['status'] = $store['status'];
									$storeData[$store['source_code']] = $sourceModalData;
									$storeData[$store['source_code']]['total_product_not_found'] = $totalSku - 1;
								}
							}
						}
					}
				}else{
					if($store['source_code'] != 'default'){
						if (array_key_exists($store['source_code'], $storeData)) {
							$storeData[$store['source_code']]['sku'] = $storeData[$store['source_code']]['sku'].','.$store['sku'];
							$skuArray = explode(',',$storeData[$store['source_code']]['sku']);
							$storeData[$store['source_code']]['total_product_not_found'] = $totalSku - count($skuArray);
						}else{
							$sourceModalData = $sourceModel->load($store['source_code'],'source_code')->getData();
							$sourceModalData['source_item_id'] = $store['source_item_id'];
							$sourceModalData['sku'] = $store['sku'];
							$sourceModalData['quantity'] = $store['quantity'];
							$sourceModalData['status'] = $store['status'];
							$storeData[$store['source_code']] = $sourceModalData;
							$storeData[$store['source_code']]['total_product_not_found'] = $totalSku - 1;

						}
					}
				}
			}
		}

		usort($storeData, function($a, $b) {
			return $a['total_product_not_found'] <=> $b['total_product_not_found'];
		});

		$data['items'] = $storeData;

		return json_encode($data);
    }
	
	public function getcity(){
		$data = [];
		$sourceModel = $this->sourceFactory->create();
		$cityCollection = $sourceModel->getCollection()
								->addFieldToSelect('city')
								->addFieldToFilter('is_pickup_location_active', ['eq' => 1])
								->addFieldToFilter('enabled', ['eq' => 1]);

		$cityData = [];
		$count = 1;
		foreach($cityCollection as $city) {
			if($city->getCity() != '') {
				if (!in_array($city->getCity(), $cityData)){
					$cityData[$count] = $city->getCity();
					$count++;
				}
			}
		}
		sort($cityData);
		$cityData[0] = "Select City";

        $cityDataNew = [];
		foreach($cityData as $cityItem){
           $cityDataNew[] = $cityItem;
		}

		$data['cities'] = $cityDataNew;
		
		return json_encode($data);
	}

}