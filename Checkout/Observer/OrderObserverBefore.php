<?php 
namespace Albatool\Checkout\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderObserverBefore implements ObserverInterface
{
    protected $_customerSession;

    public function __construct(
        \Magento\Customer\Model\Session $session
    ) {
        $this->_customerSession = $session;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {   
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/order_before_log2.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $cur_customer_id = $this->_customerSession->getCustomer()->getId(); 
        $order = $observer->getOrder();
        $order_shipping_method = $order->getShippingMethod();
        if ($this->_customerSession->isLoggedIn() && $order_shipping_method == "instore_pickup") {
            $logger->info("---BEFORE-----");
            $order->setCustomerId($cur_customer_id);
            $order->setState('new')->setStatus('pending');
            $order->setSendEmail(0);
            $order->save();
            $logger->info("---AFTER-----");
        }
    }
}