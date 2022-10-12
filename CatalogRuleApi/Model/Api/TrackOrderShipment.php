<?php

namespace Albatool\CatalogRuleApi\Model\Api;

use Psr\Log\LoggerInterface;
use Magento\Sales\Api\Data\ShipmentTrackInterface;
use Magento\Sales\Model\Order\Shipment;
use Magento\Sales\Model\ResourceModel\Order\Shipment\Track\CollectionFactory as TrackCollectionFactory;


class TrackOrderShipment
{
  protected $logger;
  protected $trackingCollection;

  public function __construct(
    LoggerInterface $logger,
    TrackCollectionFactory $collectionFactory
  )
  {

    $this->logger = $logger;
    $this->trackingCollection = $collectionFactory->create();
  }

  /**
  * @inheritdoc
  */

  public function getPost($value)
  {
    $response = ['success' => false];

    try {
      // Your Code here
      echo "YYYYYYYYY";exit;
   
      $response = ['success' => true, 'message' => $value];
    } catch (\Exception $e) {
      $response = ['success' => false, 'message' => $e->getMessage()];
      $this->logger->info($e->getMessage());
    }
    $returnArray = json_encode($response);
    return $returnArray;
  }

  
}