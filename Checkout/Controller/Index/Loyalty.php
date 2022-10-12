<?php

namespace Albatool\Checkout\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Model\Product;

class Loyalty extends Action
{
    protected $formKey;  
    protected $cart;
    protected $product;
     
    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\Data\Form\FormKey $formKey,
    \Magento\Checkout\Model\Cart $cart,
    \Magento\Catalog\Model\Product $product,
    \Magento\Framework\Webapi\Soap\ClientFactory $soapClientFactory,
    \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
    array $data = []) {
     $this->formKey = $formKey;
     $this->cart = $cart;
     $this->product = $product; 
     $this->soapClientFactory = $soapClientFactory;
     parent::__construct($context);
     $this->resultJsonFactory = $resultJsonFactory;
    }
     
    public function doRequest($mobilenumber)
      {
            $client = $this->soapClientFactory->create("http://bcalbatool.duckdns.org:8047/BC140al/WS/AL%20BATOOL%20LIVE/Codeunit/BC140APILoyaltyPoints", array('trace'=>true,
                'exceptions'=>true,
                'login'=>'apiuser',
                'password'=>'Pass@1234',
                ));
                $params["mobileno"] = $mobilenumber;
                $response = $client->__soapCall("getbalancepoints", array($params));
                return $response->return_value;
      }
    public function execute()
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/soap_loyalty.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("First Params");
        $logger->info(print_r($this->getRequest()->getParams(), true));
        if(!empty($this->getRequest()->getParam('getbalance')))
        {
            $logger->info("First INNER LOOP Mobile Number::".$this->getRequest()->getParam('getbalance'));
            $resultJson = $this->resultJsonFactory->create();
            $mob = $this->getRequest()->getParam('getbalance');
            $amount_val = (int)$this->doRequest($mob); 
            $logger->info("Mobile Number::".$mob."::POINTS::".$amount_val);
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $amount_config_val = $objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)->getValue('mycart/general/loylty_text',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                //$amount =20;
            $amount = intdiv($amount_val,$amount_config_val);
            $amount = $amount_val."=".$amount." SAR";
            return $resultJson->setData([
            'success' => $amount
            ]);
        }
        else
        {
           $logger->info("Second INNER LOOP Params");
           $logger->info(print_r($this->getRequest()->getParams(), true));
           $resultJson = $this->resultJsonFactory->create();
            $mobilenumber = $this->getRequest()->getParam('mobilenumber');
             //$mobilenumber ="032279779";
             //$mobilenumber ="0123654799";
             //$mobilenumber ="01254636256";
             
             $fee = $this->getRequest()->getParam('fee');
             //$fee=1;//die;
             //echo $mobilenumber."<br>";
             //echo $fee."<br>";
             $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
             $quote = $objectManager->create('\Magento\Quote\Model\Quote');
             $Shipping = $objectManager->create('\Magento\Quote\Api\Data\ShippingAssignmentInterface');
             $Total = $objectManager->create('\Magento\Quote\Model\Quote\Address\Total');
             $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
             $quote = $cart->getQuote();

             $quoteId = $quote->getId();
             $quote = $objectManager->create('Magento\Quote\Model\Quote')->load($quoteId);
            $itemsCollection = $cart->getQuote()->getItemsCollection();
            $itemsVisible = $cart->getQuote()->getAllVisibleItems();
            $items = $cart->getQuote()->getAllItems();
            $cart_items_onload = [];
            
             $subTotal = $quote->getSubtotal();
             $ShippingChrge = $quote->getShippingAddress()->getShippingAmount();
             $GrandTotal = $quote->getGrandTotal();

             if($fee == 0)
             {
                $logger->info("Second INNERLOOP IFF");
                $amount = 0;
                 $quote->getShippingAddress()->getShippingAmount();
                 $quote->setFee($amount);
                 $quote->setGrandTotal($quote->getGrandTotal() + $amount);
                 $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $amount);
                 $quote->save();
             }
             else
             { 
                $points = (int)$this->doRequest($mobilenumber); 
                $logger->info("Second INNERLOOP ELSE MOBILE::".$mobilenumber."::POINTS::".$points);
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $amount_config_val = $objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)->getValue('mycart/general/loylty_text',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                //$amount =20;
                $amount = intdiv($points,$amount_config_val);
                $logger->info("Second INNERLOOP ELSE AMOUNT::".$amount);
                //echo "amountt heee222::".$amount;exit;
                if($quote->getSubtotal() < $amount)
                {
                    if($GrandTotal != $ShippingChrge + $subTotal)
                     {
                        $amount_new = $GrandTotal - $ShippingChrge;   
                     }
                     else
                     { 
                        $amount_new = $quote->getSubtotal(); 
                     } 
                    
                    $amount = -$amount_new;  
                }else
                {
                    $amount = -$amount;
                }
                //echo $amount;exit;
                 $quote->getShippingAddress()->getShippingAmount();
                 $quote->setFee($amount);
                 $quote->setGrandTotal($quote->getGrandTotal() + $amount);
                 $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $amount);
                 $quote->save();
             }
        }
        $reload = $quote->getGrandTotal();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $amount_config_val = $objectManager->get(\Magento\Framework\App\Config\ScopeConfigInterface::class)->getValue('mycart/general/loylty_text',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                //$amount =20;
            $amount_val = $amount;
            $amount = intdiv($amount_val,$amount_config_val);
            $amount = $amount_val."=".$amount." SAR";
        return $resultJson->setData([
            'success' => $amount,
            'reload' => $reload
            ]);
    }
}
