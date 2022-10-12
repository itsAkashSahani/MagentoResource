<?php
namespace Albatool\StorePickupOrder\Observer;
 
 use Magento\Framework\Event\ObserverInterface;

class OrderObserver implements ObserverInterface
{


	public function execute(\Magento\Framework\Event\Observer $observer)
	{   
	  	$order = $observer->getEvent()->getOrder();
	   	$shipping_description = $observer->getEvent()->getOrder()->getData('shipping_method');
		$increment_id = $observer->getEvent()->getOrder()->getData('increment_id');
		$entity_id = $observer->getEvent()->getOrder()->getData('entity_id');

		$payment = $order->getPayment();
		$method = $payment->getMethodInstance();
		$methodCode = $method->getCode();

		$storeId = $order->getStore()->getId();

		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$helper = $objectManager->create(\Magenman\Mobilelogin\Helper\Apicall::class);
		$scopeConfig = $objectManager->create(\Magento\Framework\App\Config\ScopeConfigInterface::class);

		if($methodCode == 'cashondelivery') {
			if($shipping_description == 'instore_pickup')
			{
				$message = $scopeConfig->getValue(
					'sms/content/clickandcollect',
					\Magento\Store\Model\ScopeInterface::SCOPE_STORE,
					$storeId
				);
		
				$message = str_replace('{ORDER_NO}', $increment_id, $message);

				// $message = "Your Order #{$increment_id} Has Been Placed Successfully.\nDelivery Mode: Click and Collect";
				$helper->curlApiCall($message, $order->getBillingAddress()->getTelephone());
			}
			else {
				$message = $scopeConfig->getValue(
					'sms/content/homedelivery',
					\Magento\Store\Model\ScopeInterface::SCOPE_STORE,
					$storeId
				);
		
				$message = str_replace('{ORDER_NO}', $increment_id, $message);

				// $message = "Your Order #{$increment_id} Has Been Placed Successfully.\n Delivery Mode: Home Delivery";
				$helper->curlApiCall($message, $order->getBillingAddress()->getTelephone());				
			}
		}
	   	
    }
}