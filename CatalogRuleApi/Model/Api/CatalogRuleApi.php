<?php

namespace Albatool\CatalogRuleApi\Model\Api;

use Psr\Log\LoggerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\SalesRule\Api\Data\CouponInterface;
use Magento\SalesRule\Api\CouponRepositoryInterface;

class CatalogRuleApi
{
  protected $logger;
  private $couponRepository;
  private $searchCriteriaBuilder;

  public function __construct(
    LoggerInterface $logger,
    CouponRepositoryInterface $couponRepository,
    SearchCriteriaBuilder $searchCriteriaBuilder
  )
  {

    $this->logger = $logger;
    $this->couponRepository = $couponRepository;
    $this->searchCriteriaBuilder = $searchCriteriaBuilder;
  }

  /**
  * @inheritdoc
  */

  public function getPost()
  {
    $response = ['success' => false];

    try {
      // Your Code here
      //$coupondData = $this->getCouponDataByCode();
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
      $shoppingCartRules = $objectManager->create('Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory')->create();
      $shoppingCartRulesArr = array();
      foreach($shoppingCartRules->getData() as $shopcartrule){
      	if($shopcartrule['is_active'] == 1){
      		$shoppingCartRulesArr[] = $shopcartrule;
      	}
      }
   	  //echo "BBB::<pre>";print_r($shoppingCartRulesArr);exit;
      $response = ['success' => true, 'items' => $shoppingCartRulesArr];
    } catch (\Exception $e) {
      $response = ['success' => false, 'message' => $e->getMessage()];
      $this->logger->info($e->getMessage());
    }
    $returnArray = json_encode($response);
    return $this->getResponse($returnArray);
  }

  public function getResponse($response)
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");

        echo $response;
        exit;
    }
}