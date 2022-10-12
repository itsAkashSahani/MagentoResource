<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-credit
 * @version   1.1.15
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Albatool\CustomerAttribute\Controller\Customer;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    protected $_customerSession;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory
    ) {
        $this->resultPageFactory  = $resultPageFactory;
        $this->_customerSession = $customerSession;
        $this->redirect = $resultRedirectFactory;
        parent::__construct($context);
    }

    /**
     *
     * @return resultPage
     */
    public function execute()
    {   
        $customer = $this->_customerSession->create();
        
        if(!$customer->isLoggedIn()) {
            $redirect = $this->redirect->create();
            return $redirect->setPath('customer/account/login');
        }

        $resultPage = $this->resultPageFactory->create();
	    $resultPage->getConfig()->getTitle()->set(__("Customer Dashboard"));
	    return $resultPage;
    }
}
