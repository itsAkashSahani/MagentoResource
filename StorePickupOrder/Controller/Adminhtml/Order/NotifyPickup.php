<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Albatool\StorePickupOrder\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\InventoryInStorePickupSalesApi\Api\NotifyOrdersAreReadyForPickupInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Psr\Log\LoggerInterface;
use Albatool\StorePickupOrder\Model\ResourceModel\Sample\Collection;
use Magento\Framework\Translate\Inline\StateInterface;
use Mirasvit\Report\Model\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;



/**
 * Notify Customer of order pickup availability.
 */
class NotifyPickup extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magento_Sales::emails';

    /**
     * @var string "email" path
     */
    const XML_PATH_TRANS_IDENTITY_EMAIL = 'trans_email/ident_general/email';
    /**
     * @var string "name" path
     */
    const XML_PATH_TRANS_IDENTITY_NAME = 'trans_email/ident_general/name';

    /**
     * @var NotifyOrdersAreReadyForPickupInterface
     */
    private $notifyOrdersAreReadyForPickup;
    private $collection;

    protected $scopeConfig;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    protected $inlineTranslation;
    protected $transportBuilder;

    /**
     * @param Context $context
     * @param NotifyOrdersAreReadyForPickupInterface $notifyOrdersAreReadyForPickup
     * @param OrderRepositoryInterface $orderRepository
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        NotifyOrdersAreReadyForPickupInterface $notifyOrdersAreReadyForPickup,
        OrderRepositoryInterface $orderRepository,
        LoggerInterface $logger,
        Collection $collection,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->notifyOrdersAreReadyForPickup = $notifyOrdersAreReadyForPickup;
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
        parent::__construct($context);
        $this->collection = $collection;
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * Notify customer by email
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        try {
            $order = $this->initOrder();
        } catch (LocalizedException $e) {
            return $this->resultRedirectFactory->create()->setPath('sales/*/');
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerData = $objectManager->create('Magento\Customer\Model\Customer')
                          ->load($order->getCustomerId());

        // $mobilenumber = $customerData->getData('mobilenumber');
        $mobilenumber = $order->getBillingAddress()->getTelephone();

        $helper = $objectManager->create(\Magenman\Mobilelogin\Helper\Apicall::class);
        $helperdata = $objectManager->create(\Magenman\Mobilelogin\Helper\Data::class);
        $model = $objectManager->create('Albatool\StorePickupOrder\Model\Sample');
		$scopeConfig = $objectManager->create(\Magento\Framework\App\Config\ScopeConfigInterface::class);

        $cop =  $this->collection;
        $rendom = $helperdata->generateRandomString();
        $j =0;
        foreach ($cop as $key => $value) 
        {
            if(($mobilenumber == $value['mobile']) && ($order->getIncrementId() == $value['order_id']))
            //if($mobilenumber == $value['mobile'])
            {
                $id = $value['id'];
                $model->setId($id);
                $model->setRandomCode($rendom);
                $model->setMobile($mobilenumber);
                $model->setOrderId($order->getIncrementId());
                $model->save();
                $j=1;
                break;
            }
           
        }
        if($j != 1){
                $model->setRandomCode($rendom);
                $model->setMobile($mobilenumber);
                $model->setOrderId($order->getIncrementId());
                $model->save(); 
        }
        
        $IncrementId = (string)$order->getIncrementId();
        $messages = $helper->getPickupCustomerOtp(); 

        // $newmg = str_replace("{{order_code}}",$IncrementId,$messages);
        // $newmessages = str_replace("{{random_code}}",$rendom,$newmg);

        $storeId = $order->getStore()->getId();

        $message = $scopeConfig->getValue(
            'sms/content/readytopick',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $message = str_replace('{ORDER_NO}', $IncrementId, $message);
        $message = str_replace('{OTP}', $rendom, $message);

        // $message = "Your Order #{$IncrementId} Is Ready To Pickup, Your OTP Is {$rendom}";
        $result = $this->notifyOrdersAreReadyForPickup->execute([(int)$order->getEntityId()]);

        if ($result->isSuccessful()) {

            if ($order->getCustomerIsGuest()) {
                $customerName = $order->getBillingAddress()->getName();
            } else {
                $customerName = $order->getCustomerName();
            }
    
            $customerEmail = $order->getCustomerEmail();

            $storeName = $order->getShippingAddress()->getName();
    
            $data = [
                'customerName' => $customerName,
                'customerEmail' => $customerEmail,
                'storeName' => $storeName,
                'orderId' => $IncrementId,
                'otp' => $rendom
            ];

            $this->sendReadyForPickup($data, $order->getStore()->getId());

            $helper->curlApiCall($message,$mobilenumber);
            $this->messageManager->addSuccessMessage(__('The customer has been notified and Send the OTP.'));
        } else {
            $error = current($result->getErrors());
            $this->messageManager->addErrorMessage($error['message']);
        }

        $order = $objectManager->create('\Magento\Sales\Model\Order')->load($order->getEntityId());
        $order->setStatus('pickup');
        $order->save();
        return $this->resultRedirectFactory->create()->setPath(
            'sales/order/view',
            [
                'order_id' => $order->getEntityId(),
            ]
        );
    }

    /**
     * Initialize order model instance
     *
     * @return OrderInterface
     * @throws InputException
     * @throws NoSuchEntityException
     * @see \Magento\Sales\Controller\Adminhtml\Order::_initOrder
     */
    private function initOrder(): OrderInterface
    {
        $id = $this->getRequest()->getParam('order_id');
        try {
            $order = $this->orderRepository->get($id);
        } catch (NoSuchEntityException|InputException $e) {
            $this->messageManager->addErrorMessage(__('This order no longer exists.'));
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
            throw $e;
        }

        return $order;
    }

    public function sendReadyForPickup($data, $storeId)
    {
        try {            
            $email = $data['customerEmail'];
            $name = $data['customerName'];
            $storeName = $data['storeName'];
            $orderId = $data['orderId'];
            $otp = $data['otp'];

            $this->inlineTranslation->suspend();

            $senderName = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_NAME);
            $senderEmail = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_EMAIL);

            $templateId = $this->scopeConfig->getValue(
                'sales_email/order_ready_for_pickup/guest_template',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );

            $sender = [
                'name' => $senderName,
                'email' => $senderEmail
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateVars([
                    'email' => $email,
                    'name' => $name,
                    'storeName' => $storeName,
                    'orderId' => $orderId,
                    'otp' => $otp
                ])
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $storeId,
                    ]
                )
                ->setFrom($sender)
                ->addTo($email);

            $transport->getTransport()->sendMessage();

            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
    }
}
