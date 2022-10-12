<?php
namespace Albatool\StorePickupOrder\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    protected $transportBuilder;
    protected $storeManager;
    protected $inlineTranslation;

    public function __construct(
        Context $context,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $state
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $state;
        parent::__construct($context);
    }

    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function sendEmail($data,$receiverInfo)
    {
    	// this is an example and you can change template id,fromEmail,toEmail,etc as per your need.
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
	    $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
       
        $templateId = 'add_contract_devices_template'; // template id
        
        try {
            // template variables pass here
            $storeId = $this->storeManager->getStore()->getId();

            $senderInfo = [
                'name' => $scopeConfig->getValue('trans_email/ident_support/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
                'email' => $scopeConfig->getValue('trans_email/ident_support/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            ];
            $this->inlineTranslation->suspend();

            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];
           
            $transport = $this->transportBuilder->setTemplateIdentifier($templateId, $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($data)
                ->setFrom($senderInfo)
                ->addTo($receiverInfo)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            //echo "sent";die;
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }

     public function pickupNotifyEmail($templateVars,$senderInfo,$receiverInfo,$temp)
    {
        
        $templateId = $temp; // template id
        
        try {         
            $this->inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $templateOptions = [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];
           
            $transport = $this->transportBuilder->setTemplateIdentifier($templateId, $storeScope)
                ->setTemplateOptions($templateOptions)
                ->setTemplateVars($templateVars)
                ->setFrom($senderInfo)
                ->addTo($receiverInfo)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }


    public function sucessPickup($incrementid)
    {

       $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
       $Pickupsource = $objectManager->create(\Albatool\StorePickupOrder\Block\Pickupsource::class);
       $orderInterface = $objectManager->create('Magento\Sales\Api\Data\OrderInterface'); 
       $order = $orderInterface->loadByIncrementId($incrementid);
       echo $order->getData('entity_id');
       echo $order->getId();die;
       $pickup_store_code = $Pickupsource->getDatas($order->getData('entity_id'));
            $vlue="";
        foreach ($pickup_store_code->getData() as $key => $value) {
            $vlue = $value['pickup_location_code'];//die;
         }
         echo $vlue;
        $sourceList = $objectManager->get('\Magento\Inventory\Model\ResourceModel\Source\Collection');
        $sourceListArr = $sourceList->load();
        $sourceList = array();
        $option_name="";
        $source_Email="";
        foreach ($sourceListArr as $sourceItemName) {
            if($vlue == $sourceItemName->getCode())
            {
                echo  "--->".$sourceItemName->getCode();die;
                $source_Email = $sourceItemName->getEmail();
                $source_Name = $sourceItemName->getName(); 
                $source_Phone = $sourceItemName->getPhone(); 
                break;
            }
         }
        
         /*Store email and SMS*/
            $receiverInfo = $source_Email;
             $templateVars = array(
                    'increment_id' => $increment_id
                );
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $emilhelper = $objectManager->create(\Albatool\StorePickupOrder\Helper\Data::class);
            //echo "<pre>";print_r($templateVars);//echo $receiverInfo;
            $helper = $objectManager->create(\Magenman\Mobilelogin\Helper\Apicall::class);
            $IncrementId = (string)$increment_id;
            $messages = $helper->getPickupStore(); 
            //echo $messages;
            //echo $IncrementId;die;
            $emilhelper->sendEmail($templateVars,$receiverInfo);
            
            $newmessages = str_replace("{{random_code}}",$IncrementId,$messages);
           
            $helper->curlApiCall($newmessages,$source_Phone);
    }
}
