<?php
namespace Albatool\StorePickupOrder\Controller\Adminhtml\Standard;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Translate\Inline\StateInterface;
use Mirasvit\Report\Model\Mail\Template\TransportBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Verify extends Action
{
    protected $request;

    /**
     * @var string "email" path
     */
    const XML_PATH_TRANS_IDENTITY_EMAIL = 'trans_email/ident_general/email';
    /**
     * @var string "name" path
     */
    const XML_PATH_TRANS_IDENTITY_NAME = 'trans_email/ident_general/name';

    protected $scopeConfig;

    protected $inlineTranslation;
    protected $transportBuilder;


    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
    }
    public function execute()
     {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->create('Albatool\StorePickupOrder\Model\Sample');
        $collection = $model->getCollection()->getData();
        $otp = $this->getRequest()->getParam('otp');        
        $mobile = $this->getRequest()->getParam('mobile');        
        $ordeid = $this->getRequest()->getParam('ordeid'); 
        $paym_type = $this->getRequest()->getParam('paym_type'); 
        $otp_desc = $this->getRequest()->getParam('otp_desc');
        $orderentityid = $this->getRequest()->getParam('orderentityid');   
        //echo "OTP TYPE:::".$paym_type."::".$otp_desc."::".$ordeid;exit;
        $set=0;
        /*save order otp custom attributes*/
            //echo "ORDER III::";$value['order_id'];exit;
            $order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get($orderentityid);
            if($paym_type == "pickup_cod"){
                $order->setPickupPaymentType("COD");                                
            }
            else{
                $order->setPickupPaymentType("Online");
            }
            $order->setPickupPaymentDesc($otp_desc);
            $order->save();
        /*end save order otp custom attributes*/
        foreach ($collection as $key => $value) 
        { 
            if(($mobile == $value['mobile']) && ($ordeid == $value['order_id']))
            {
                if($otp == $value['random_code'])
                {
                    $model->setId($value['id']);
                    $model->setRandomCode($otp);
                    $model->setMobile($value['mobile']);
                    $model->setOrderId($value['order_id']);
                    $model->setIsVerify('1');
                    $model->save();
                    $set=1;
                    $InvoiceService = $objectManager->create('\Magento\Sales\Model\Service\InvoiceService');
                    $transaction = $objectManager->create('\Magento\Framework\DB\Transaction');
                    $loadorder = $objectManager->create('\Magento\Sales\Api\Data\OrderInterfaceFactory')->create();
                    $order = $loadorder->loadByIncrementId($ordeid);
                    $invoice = $InvoiceService->prepareInvoice($order);
                    $invoice->register();
                    $invoice->save();
                    $transactionSave = $transaction->addObject($invoice)->addObject($invoice->getOrder());
                    $invoice->getOrder()->setIsInProcess(true);
                    $transactionSave->save();

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
                        'orderId' => $ordeid
                    ];
        
                    $this->sendPickupCollected($data, $order->getStore()->getId());

                    $order->addStatusHistoryComment(__('Invoice has been created #%1.', $invoice->getId()))->setIsCustomerNotified(true)->save();

                    $order->setState('complete')->setStatus('collected');
                    $order->save();

                    break;
                }else{
                    $set=0;
                }
            }
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($set);
        return $resultJson;
    }

    public function sendPickupCollected($data, $storeId)
    {
        try {            
            $email = $data['customerEmail'];
            $name = $data['customerName'];
            $storeName = $data['storeName'];
            $orderId = $data['orderId'];

            $this->inlineTranslation->suspend();

            $senderName = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_NAME);
            $senderEmail = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_EMAIL);

            $templateId = $this->scopeConfig->getValue(
                'sales_email/order_ready_for_pickup/template',
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
