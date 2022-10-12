<?php
namespace Albatool\Checkout\Observer;
 
use Magento\Framework\Event\ObserverInterface;

class OrderObserver implements ObserverInterface
{
	public function execute(\Magento\Framework\Event\Observer $observer)
	{   
	  	$order = $observer->getEvent()->getOrder();

		$payment = $order->getPayment();
		$method = $payment->getMethodInstance();
		$methodCode = $method->getCode();

        $order->setData('customer_mobilenumber', $order->getBillingAddress()->getTelephone());

        // if ($order->getCustomerIsGuest()) {
        //     $customerFactory = $objectManager->get('\Magento\Customer\Model\CustomerFactory')->create();
        //     $customer = $customerFactory->load($order->getCustomerId());
        //     $mob = $customer->getData('mobilenumber');
        //     $customerName = $order->getBillingAddress()->getName();
        // } else {
        //     $customerName = $order->getCustomerName();
        // }

		if($methodCode == 'cashondelivery') {
            $order->setState('new')->setStatus('processing');
        }
        else {
            $order->setState('new')->setStatus('pending_payment');
            $orderTotal = $order->getGrandTotal();
            $order->setBaseTotalDue(0);
            $order->setTotalDue(0);
            $order->setTotalPaid($orderTotal);
            $order->setBaseTotalPaid($orderTotal);
        }

        $order->save();
    }
}
