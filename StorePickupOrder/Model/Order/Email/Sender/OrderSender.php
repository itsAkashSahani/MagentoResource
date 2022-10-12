<?php

namespace Albatool\StorePickupOrder\Model\Order\Email\Sender;

use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Container\OrderIdentity;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Framework\Event\ManagerInterface;

class OrderSender extends \Magento\Sales\Model\Order\Email\Sender\OrderSender
{
    public function __construct(
        Template $templateContainer,
        OrderIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        Renderer $addressRenderer,
        PaymentHelper $paymentHelper,
        OrderResource $orderResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        ManagerInterface $eventManager
    ) {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer, $paymentHelper, $orderResource, $globalConfig, $eventManager);
    }

    protected function prepareTemplate(Order $order)
    {
        // Call parent
        parent::prepareTemplate($order);

        if($order->getShippingMethod() == 'instore_pickup') {
            $this->templateContainer->setTemplateId($this->getPickupTemplateId($order->getStoreId()));
        }      
    }

    public function getPickupTemplateId($storeId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$scopeConfig = $objectManager->create(\Magento\Framework\App\Config\ScopeConfigInterface::class);

        return $scopeConfig->getValue(
            'sales_email/order/pickup_order',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

}