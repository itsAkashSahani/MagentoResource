<?php
namespace Albatool\StorePickupOrder\Block\Adminhtml;

use Magento\Framework\View\Element\Template\Context;
use Albatool\StorePickupOrder\Model\Data;
use Magento\Framework\View\Element\Template;

class StandardOrder extends \Magento\Backend\Block\Template
{

    protected $_orderCollectionFactory;
    protected $_orderRepository;
    protected $orderFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Albatool\StorePickupOrder\Model\Data $model,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\OrderRepository $orderRepository,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        array $data = [] 
    ) {
        $this->model = $model;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderRepository = $orderRepository;
         $this->orderFactory = $orderFactory;
        parent::__construct($context, $data);

    }
   
    public function getOrderCollection()
   {
       $collection = $this->_orderCollectionFactory->create()
         ->addAttributeToSelect('*')
         ->addFieldToFilter('shipping_method','instore_pickup')
         ->setOrder('entity_id','desc');
        return $collection;
     
    }

    public function getDatas($orderpickId)
    {
        $Datas = $this->model->getCollection()
                             ->addFieldToFilter('order_id',$orderpickId);
        return $Datas;
    }

}

