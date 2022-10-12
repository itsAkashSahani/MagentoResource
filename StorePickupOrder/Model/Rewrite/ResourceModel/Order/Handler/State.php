<?php

namespace Albatool\StorePickupOrder\Model\Rewrite\ResourceModel\Order\Handler;

use Magento\Sales\Model\Order;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Albatool\StorePickupOrder\Model\ResourceModel\Sample\Collection;


class State extends \Magento\Sales\Model\ResourceModel\Order\Handler\State
{
     public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        Collection $collection,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;
        $this->collection = $collection;
    }
	public function check(Order $order)
    { 
        $currentState = $order->getState();
        if ($currentState == Order::STATE_NEW && $order->getIsInProcess()) {
            $order->setState(Order::STATE_PROCESSING)
                ->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_PROCESSING));
            $currentState = Order::STATE_PROCESSING;
        }

        if (!$order->isCanceled() && !$order->canUnhold() && !$order->canInvoice()) {
            if (in_array($currentState, [Order::STATE_PROCESSING, Order::STATE_COMPLETE])
                && !$order->canCreditmemo()
                && !$order->canShip()
                && $order->getIsNotVirtual()
            ) {
                $order->setState(Order::STATE_CLOSED)
                    ->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_CLOSED));
            } elseif ($currentState === Order::STATE_PROCESSING && !$order->canShip()) {
                $order->setState(Order::STATE_COMPLETE)
                    ->setStatus($order->getConfig()->getStateDefaultStatus(Order::STATE_COMPLETE));
            }
        }
        // $newState = $order->getStatus();
        // if($newState == 'pickup'){
        //     echo "lkjhg--------";
        //     $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        //     $model = $objectManager->create('Albatool\StorePickupOrder\Model\SmsFactory')->load();
        //     foreach ($model as $key => $value) {
        //        echo $value->getRandomCode();
        //        echo $value->getId();
        //     }
            // $model->setRandomCode('2122');
            // $model->setMobile('0987654321');
            // $model->save();
           // $data = array("id"=>"1","random_code"=>"3777","mobile"=>"9974291828");
            
            // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            // $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
            // $scopeConfig = $objectManager->create('\Magento\Framework\App\Config\ScopeConfigInterface');
            // $receiverInfo = [
            //     'name' => $order->getCustomerFirstName(),
            //     'email' => $order->getCustomerEmail(),
            // ];
            // $senderInfo = [
            //     'name' => $scopeConfig->getValue('trans_email/ident_support/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            //     'email' => $scopeConfig->getValue('trans_email/ident_support/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE),
            // ];
            //  $templateVars = array(
            //         'increment' => $order->getIncrementId(),
            //         'created' => $order->getCreatedAt(),
            //     );

            // // $objectManager->get('Albatool\StorePickupOrder\Helper\Email')->pickupNotifyEmail(
            // //               $templateVars,
            // //               $senderInfo,
            // //               $receiverInfo,
            // //               'customer_notification_pickup_order'
            // //             );
            // $customerId = $order->getCustomerId();
            // $helper = $objectManager->create(\Magenman\Mobilelogin\Helper\Apicall::class);
            // $customerData = $objectManager->create('Magento\Customer\Model\Customer')
            //                   ->load($customerId);

            // $mobilenumber = $customerData->getData('mobilenumber');
            // $IncrementId = (string)$order->getIncrementId();
            // $messages = $helper->getPickupCustomerStore(); 
            // $newmessages = str_replace("{{random_code}}",$IncrementId,$messages);
        //}
        //echo "new123456678".$newState;die;
        return $this;
        
    }
}