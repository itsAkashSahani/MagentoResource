<?php

namespace Albatool\Checkout\Controller\Account;

class Signin extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
	
	protected $resultRedirect;
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
	
    public function execute()
    {
		$resultPage = $this->resultPageFactory->create();
		$resultPage->getConfig()->getTitle()->set(__("Login Page"));
		return $resultPage;   
    }
}
