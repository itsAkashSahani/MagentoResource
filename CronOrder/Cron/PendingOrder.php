<?php

namespace Albatool\CronOrder\Cron;

use Magento\Framework\App\ResourceConnection;

class PendingOrder
{
	protected $request;

	protected $jsonResultFactory;

	private $resourceConnection;

	protected $_orderCollectionFactory;

	protected $orderManagement;

	public function __construct(
        \Magento\Framework\Webapi\Rest\Request $request,
        \Magento\Framework\Controller\Result\JsonFactory $jsonResultFactory,
        ResourceConnection $resourceConnection,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Api\OrderManagementInterface $orderManagement
    ) {
        $this->request = $request;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->resourceConnection = $resourceConnection;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->orderManagement = $orderManagement;
    }

	public function execute()
	{

		$writer = new \Zend_Log_Writer_Stream(BP . '/var/log/cron_pending_orders.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$logger->info("---Start Of Pending Orders NEW Log----------");
		$pendingday = $objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('pendingordergen/general/pendingorderday');		
		// $pendingday = "1";
		$pendingday = "-".$pendingday." days";
		$prev_date = date('Y-m-d', strtotime($pendingday));
		//$from_date = '2022-03-01 10:35:39';
		$collection = $this->_orderCollectionFactory->create()->addAttributeToSelect('*')
		->addFieldToFilter('shipping_method',
                ['eq' => 'instore_pickup']
            )
		->addAttributeToFilter('status', ['in' => 'processing'])
   		->addAttributeToFilter('created_at', ['lteq'=>$prev_date.' 00:00:00']);
		
		foreach($collection as $coll){
				$coll_order_id[] = $coll->getId();
				$this->orderManagement->cancel($coll->getId());
		}
        $logger->info(print_r($coll_order_id, true));
        $logger->info("---End Of Pending Orders NEW Log----------");
		return $this;

	}
}