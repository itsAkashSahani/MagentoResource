<?php
namespace Albatool\Checkout\Block;

class Loyalty extends \Magento\Framework\View\Element\Template
{    
    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,       
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Webapi\Soap\ClientFactory $soapClientFactory,
        array $data = []
        )
        { 
            $this->_registry = $registry;
            $this->soapClientFactory = $soapClientFactory;
            parent::__construct($context, $data);
        }

    public function doRequest($mobilenumber)
      {
        //echo "sdfgh";die;
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/soap_loyalty.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("*****My Account Total Points*******");
            $client = $this->soapClientFactory->create("http://bcalbatool.duckdns.org:8047/BC140al/WS/AL%20BATOOL%20LIVE/Codeunit/BC140APILoyaltyPoints", array('trace'=>true,
                'exceptions'=>true,
                'login'=>'apiuser',
                'password'=>'Pass@1234',
                ));
                $params["mobileno"] = $mobilenumber;
                $response = $client->__soapCall("getbalancepoints", array($params));
                //$currmonth = $client->__soapCall("getbalancepointscurrmonth", array($params));
                //return $currmonth->return_value;
        $logger->info("Mobile Number::".$mobilenumber."::POINTS::".$response->return_value);
        $logger->info("*****End My Account Total Points*******");
                return $response->return_value;
      }
    public function pointsCurrmonth($mobilenumber)
      {
        //echo "sdfgh";die;
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/soap_loyalty.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("*****My Account Curmonth Points*******");
            $client = $this->soapClientFactory->create("http://bcalbatool.duckdns.org:8047/BC140al/WS/AL%20BATOOL%20LIVE/Codeunit/BC140APILoyaltyPoints", array('trace'=>true,
                'exceptions'=>true,
                'login'=>'apiuser',
                'password'=>'Pass@1234',
                ));
                $params["mobileno"] = $mobilenumber;
                //$response = $client->__soapCall("getbalancepoints", array($params));
                $currmonth = $client->__soapCall("getbalancepointscurrmonth", array($params));
                $logger->info("Mobile Number::".$mobilenumber."::POINTS::".$currmonth->return_value);
                $logger->info("*****End My Account Curmonth Points*******");
                return $currmonth->return_value;
      }
}
