<?php
namespace Albatool\ValidateMobile\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;

class CheckUniquePhonenumberObserver implements ObserverInterface
{

    protected $_customerCollectionFactory;
    protected $request;

    public function __construct(
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->_customerCollectionFactory = $customerCollectionFactory;
        $this->request = $request;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();
        $customerCollection= $this->_customerCollectionFactory->create();

        $customerCollection->addAttribuTeToFilter('mobilenumber', $customer->getData('mobilenumber'));
        $ids = $customerCollection->getAllIds();
        
        if($this->request->getFullActionName() == 'customer_account_editPost') {
            if(in_array($customer->getId(), $ids)) {
                $customerCollection->addAttribuTeToFilter('entity_id',array('eq' => (int)$customer->getId()));
                $customer->setData('dob', $customerCollection->getFirstItem()->getData('dob'));
                $customer->setData('email', $customerCollection->getFirstItem()->getData('email'));
                return;
            }
            throw new LocalizedException(
                __('Can not change mobile number.')
            );
        }

        if ($customer->getId()) {   
            $customerCollection->addAttribuTeToFilter('entity_id',array('neq' => (int)$customer->getId()));   
        }
        if($customerCollection->getSize() > 0){
            throw new LocalizedException(
                __('A customer with the same Mobile already exists .')
            );
        }

    }

}