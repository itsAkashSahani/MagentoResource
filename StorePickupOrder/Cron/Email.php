<?php
namespace Albatool\StorePickupOrder\Cron;

class Email
{

		protected $_orderCollectionFactory;
	   /** @var \Magento\Sales\Model\ResourceModel\Order\Collection */
	   protected $orders;
	   protected $logger;

	   public function __construct(
		//\Magento\Framework\View\Element\Template\Context $context,
	  	\Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
	  	\Psr\Log\LoggerInterface $logger,
	  	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig

	  	//array $data = []
	) {
		$this->logger = $logger;
		$this->_scopeConfig = $scopeConfig;
	    $this->_orderCollectionFactory = $orderCollectionFactory;     
	    //parent::__construct($context, $data);        
	}

	public function execute()
	{	
		$enabled = $this->_scopeConfig->getValue('helloworld/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		if($enabled == 1){

			$date_now =  date('Y-m-d H:i:s');
		$days = $this->_scopeConfig->getValue('helloworld/general/days', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $exp = date('Y-m-d H:i:s', strtotime($date_now. ' + '.$days.' days')); 
		if (!$this->orders) 
        {
          	$Collection = $this->_orderCollectionFactory->create()
	            	->addFieldToSelect('*')
	            	->addFieldToFilter('shipping_method', 'instore_pickup')
	            	->addFieldToFilter('status', array('nin' => 'complete','cancel'))
	            	->addFieldToFilter('created_at', ['lteq' => $exp]);
        }

		if ($Collection && count($Collection) > 0)
        	{
		    foreach ($Collection AS $order) 
		    { 
		        if(!empty($order->getCreatedAt()))
		        {
		        	$Created = $order->getCreatedAt();
		        	$IncrementId = $order->getIncrementId();
		        	$OrderId = $order->getOrderId();
		            $customerEmail =$order->getCustomerEmail();
		            $customerId =$order->getCustomerId();
		            $velidtion = $order->getPickupSent();
		           \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug('Rucha');
		            if((!empty($velidtion)) && ($velidtion == 1))
		            {
		            	echo "if";
		            	$pickup_cen = $order->getPickupCencledt($setcencle);
		            	$date_now = new DateTime();
 						$date1    = new DateTime($pickup_cen);

			            if($date_now < $date1)
			            {
			            	echo "date_now------".$date_now;
			            	echo "date1------".$date1;
			            	$this->_orderCollectionFactory->cancel($OrderId);
			            }
		            }else
		            {
		            	echo "else";
            			echo ("IncrementId-------".$IncrementId);	
	            		echo ("customerEmail------".$customerEmail);
	            		echo ("customerEmail------".$Created);		
			            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			            $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
			            $scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
			            $receiverInfo = [
			                'name' => $order->getCustomerFirstName(),
			                'email' => $order->getCustomerEmail(),
			            ];
			            $senderInfo = [
			                'name' => $scopeConfig->getValue('trans_email/ident_support/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
			                'email' => $scopeConfig->getValue('trans_email/ident_support/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
			            ];
			             $templateVars = array(
			                    'increment' => $order->getIncrementId(),
			                    'created' => $order->getCreatedAt(),
			                );
						$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            			$helper = $objectManager->create(\Magenman\Mobilelogin\Helper\Apicall::class);
            			$customerData = $objectManager->create('Magento\Customer\Model\Customer')
                                          ->load($customerId);

			            $mobilenumber = $customerData->getData('mobilenumber');
			            $IncrementId = (string)$order->getIncrementId();
			            $messages = $helper->getPickupCustomerStore(); 
			            $newmessages = str_replace("{{random_code}}",$IncrementId,$messages);
			            $helper->curlApiCall($newmessages,$mobilenumber);
			             \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($newmessages);
			              \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($mobilenumber);
			              \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug($mobilenumber);
	            		$objectManager->get('Albatool\StorePickupOrder\Helper\Email')->pickupNotifyEmail(
	                      $templateVars,
	                      $senderInfo,
	                      $receiverInfo,
	                      'customer_notification_pickup_order'
	                    );
	                    $datetime = new DateTime('tomorrow');
					$setcencle = $datetime->format('Y-m-d H:i:s');
					$order->setPickupCencledt("sdfgh");
	            	$order->setPickupSent("1");
		            }
		        }
			}
		}

		}
		return $this;

	}
}