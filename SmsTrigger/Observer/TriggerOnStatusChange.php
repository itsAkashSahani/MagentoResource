<?php

namespace Albatool\SmsTrigger\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenman\Mobilelogin\Helper\Apicall;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Albatool\SmsTrigger\Helper\Data;

class TriggerOnStatusChange implements ObserverInterface
{
    protected $apiCall;
    protected $customer;
    protected $helper;
    protected $scopeConfig;

    const XML_PATH_STATUS = 'invoicepdfqrgen/general/status';

    public function __construct(
		Apicall $apiCall,
        CustomerRepositoryInterface $customer,
        ScopeConfigInterface $scopeConfig,
        Data $helper
	) {
		$this->apiCall = $apiCall;
		$this->customer = $customer;
        $this->scopeConfig = $scopeConfig;
		$this->helper = $helper;
	}

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $invoiceId = false;
        $storeId = $order->getStore()->getId();

        $orderStatus = $this->scopeConfig->getValue(
            self::XML_PATH_STATUS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if($orderStatus == '') {
            $orderStatus = "delivered";
        }

        foreach($order->getInvoiceCollection() as $invoice) {
            $invoiceId = $invoice->getId();
        }

        if($order->getState() == 'new') {
            return;
        }

        if($order->getStatusLabel() == 'Pending' || $order->getStatusLabel() == 'Payfort New Order') {
            return;
        }
        
        $templateId = 1;

        if ($order->getCustomerIsGuest()) {
            $customerName = $order->getBillingAddress()->getName();
        } else {
            $customerName = $order->getCustomerName();
        }

        $customerEmail = $order->getCustomerEmail();

        $data = [
            'orderStatus' => $order->getStatusLabel(),
            'customerName' => $customerName,
            'customerEmail' => $customerEmail,
            'orderId' => $order->getIncrementId()
        ];

        // $this->helper->sendEmailAfterOrderChange($data, $templateId, $storeId);
        if($order->getStatus() == $orderStatus) {
            $this->helper->sendInvoicePdf($data, $invoiceId, $storeId);
        }

        if($order->getStatus() == 'canceled') {
            if($order->getShippingMethod() == 'instore_pickup') {
                $data['pickup'] = true;
                $data['storeName'] = $order->getShippingAddress()->getFirstname() . " " . $order->getShippingAddress()->getLastname();
            }

            $this->helper->sendOrderCancelMail($data, $storeId);

            $mobilenumber = $order->getBillingAddress()->getTelephone();

            $message = $this->scopeConfig->getValue(
                'sms/content/ordercancel',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );

            $message = str_replace('{ORDER_NO}', $order->getIncrementId(), $message);

            // $message = "Order status : {$order->getStatusLabel()} of Order Id : #{$order->getIncrementId()}";
            
            if($this->apiCall->isEnable()) {
                $this->apiCall->curlApiCall($message, $mobilenumber);
            }
        }
    }
}