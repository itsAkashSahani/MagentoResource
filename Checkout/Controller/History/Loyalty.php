<?php

namespace Albatool\Checkout\Controller\History;

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
                $currmonth = $client->__soapCall("getbalancepointscurrmonth", array($params));
                //return $currmonth->return_value;

                return $response->return_value;
      }
    public function execute()
    {
        
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
        
        // if(!empty($this->getRequest()->getParam('getbalance')))
        // {
        //     $resultJson = $this->resultJsonFactory->create();
        //     $mob = $this->getRequest()->getParam('getbalance');
           // $mobilenumber ="0123654799";
            //echo $amount = (int)$this->doRequest($mobilenumber); //die;
            // return $resultJson->setData([
            // 'success' => $amount
            // ]);
           
        // }
        // else
        // {
        //    $resultJson = $this->resultJsonFactory->create();
        //     $mobilenumber = $this->getRequest()->getParam('mobilenumber');
        //      //$mobilenumber ="032279779";
        //      //$mobilenumber ="0123654799";
        //      //$mobilenumber ="01254636256";
             
        //      $fee = $this->getRequest()->getParam('fee');
        //      //$fee=1;//die;
        //      echo $mobilenumber."<br>";
        //      echo $fee."<br>";
        //      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //      $quote = $objectManager->create('\Magento\Quote\Model\Quote');
        //      $Shipping = $objectManager->create('\Magento\Quote\Api\Data\ShippingAssignmentInterface');
        //      $Total = $objectManager->create('\Magento\Quote\Model\Quote\Address\Total');
        //      $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
        //      $quote = $cart->getQuote();

        //      $quoteId = $quote->getId();
        //      $quote = $objectManager->create('Magento\Quote\Model\Quote')->load($quoteId);
        //     $itemsCollection = $cart->getQuote()->getItemsCollection();
        //     $itemsVisible = $cart->getQuote()->getAllVisibleItems();
        //     $items = $cart->getQuote()->getAllItems();
        //     $cart_items_onload = [];
            
        //      $subTotal = $quote->getSubtotal();
        //      $ShippingChrge = $quote->getShippingAddress()->getShippingAmount();
        //      $GrandTotal = $quote->getGrandTotal();

        //      if($fee == 0)
        //      {
        //         $amount = 0;
        //          $quote->getShippingAddress()->getShippingAmount();
        //          $quote->setFee($amount);
        //          $quote->setGrandTotal($quote->getGrandTotal() + $amount);
        //          $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $amount);
        //          $quote->save();
        //      }
        //      else
        //      { 
        //         $amount = (int)$this->doRequest($mobilenumber); 
        //         //$amount =20;
        //         if($quote->getSubtotal() < $amount)
        //         {
        //             if($GrandTotal != $ShippingChrge + $subTotal)
        //              {
        //                 $amount_new = $GrandTotal - $ShippingChrge;   
        //              }
        //              else
        //              { 
        //                 $amount_new = $quote->getSubtotal(); 
        //              } 
                    
        //             $amount = -$amount_new;  
        //         }else
        //         {
        //             $amount = -$amount;
        //         }
        //         echo $amount;
        //          $quote->getShippingAddress()->getShippingAmount();
        //          $quote->setFee($amount);
        //          $quote->setGrandTotal($quote->getGrandTotal() + $amount);
        //          $quote->setBaseGrandTotal($quote->getBaseGrandTotal() + $amount);
        //          $quote->save();
        //      }
        // }
        // $reload = $quote->getGrandTotal();
        // return $resultJson->setData([
        //     'success' => $amount,
        //     'reload' => $reload
        //     ]);
    }
}
