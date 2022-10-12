<?php
namespace Albatool\Checkout\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class InvoicePayAfter implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getInvoice()->getOrder();
        
        $order->setState('processing')->setStatus('approved');
        $order->save();
    }
}
