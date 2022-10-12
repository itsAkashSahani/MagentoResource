<?php
namespace Albatool\StorePickupOrder\Block\Adminhtml\Order\View;
use Albatool\StorePickupOrder\Model\ResourceModel\Sample\Collection;

class View extends \Magento\Backend\Block\Template
{
   private $_coreRegistry;
   private $collection;

 
    /**
     * View constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
                Collection $collection,
        \Magento\Framework\Registry             $registry,
        array                                   $data = []
    )
    {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
        $this->collection = $collection;

    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }
 
    /**
     * Retrieve order model instance
     *
     * @return int
     *Get current id order
     */
    public function getOrderId()
    {
        return $this->getOrder()->getEntityId();
    }
 
    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getOrderIncrementId()
    {
        return $this->getOrder()->getIncrementId();
    }

    /**
     * Retrieve customer id
     *
     * @return string
     */
    public function getMobilenumber()
    {
        $order = $this->getOrder();
        //    $CustomerId = $this->getOrder()->getCustomerId();
        //    $Email = $this->getOrder()->getCustomerEmail();
        //    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        //    $customerData = $objectManager->create('Magento\Customer\Model\Customer')
        //                       ->load($CustomerId);
        //    $mobilenumber = $customerData->getData('mobilenumber');
        return $order->getBillingAddress()->getTelephone();
    }
 
    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getOrderShippingMethod()
    {
        return $this->getOrder()->getShippingMethod();
    }
    /**
     * Retrieve order increment id
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->getOrder()->getCustomerEmail();
    }
 
    public function getStatus()
    {
        return $this->getOrder()->getStatus();
    }

    public function getOrderOtp($orderId) {
        return $this->collection->addAttributeToFilter('order_id', array('eq' => $$orderId));
    }
}