<?php

namespace Albatool\Checkout\Controller\Account;

class Guest extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory ;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context, 
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory  = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     *
     * @return resultPage
     */
    public function execute()
    {   
        $resultPage = $this->resultPageFactory->create();
	    $resultPage->getConfig()->getTitle()->set(__("Guest Page"));
	    return $resultPage;
    }
}