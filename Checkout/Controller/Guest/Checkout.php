<?php

namespace Albatool\Checkout\Controller\Guest;

use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\QuoteFactory;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Controller\ResultFactory; 
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\UrlInterface;
use Albatool\Checkout\Model\Cookie\RememberMe;

class Checkout extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;
	
	/**
     * @var AccountManagementInterface
     */
    protected $customerAccountManagement;

	/**
     * @var QuoteFactory
     */
    protected $quoteFactory;
	
	/**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
    * @var anagerInterface
    */
    protected $messageManager;

	/**
    * @var JsonFactory
    */
	protected $resultJsonFactory;
	
	/**
    * @var UrlInterface
    */
	protected $_urlInterface;
	
	/**
    * @var RememberMe
    */
	protected $rememberMe;
	
    /**
     * @param Context $context
     * @param AccountManagementInterface $customerAccountManagement
     * @param CheckoutSession $checkoutSession
	 * @param QuoteFactory $quoteFactory
     * @param StoreManagerInterface $storeManager
     * @param ManagerInterface $messageManager
	 * @param JsonFactory $resultJsonFactory
	 * @param UrlInterface $urlInterface
	 * @param RememberMe $rememberMe
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
		AccountManagementInterface $customerAccountManagement,
		QuoteFactory $quoteFactory,
        StoreManagerInterface $storeManager,
	    ManagerInterface $messageManager,
		JsonFactory $resultJsonFactory,
		UrlInterface $urlInterface,
		RememberMe $rememberMe
	) {
        $this->checkoutSession = $checkoutSession;
		$this->customerAccountManagement = $customerAccountManagement;
		$this->quoteFactory = $quoteFactory;
		$this->storeManager = $storeManager;
        $this->messageManager = $messageManager;
		$this->resultJsonFactory = $resultJsonFactory; 
	    $this->_urlInterface = $urlInterface;
	    $this->rememberMe = $rememberMe;
        parent::__construct($context);
    }

    public function execute()
    {
		$requestData = $this->getRequest()->getParams();
        $result = $this->resultJsonFactory->create();
		$signUrl = $this->_urlInterface->getUrl('customer/account/signin', ['_secure' => true]);
		$checkoutUrl = $this->_urlInterface->getUrl('checkout', ['_secure' => true]);
		if(isset($requestData['remember_me'])){
			$this->rememberMe->delete();
			$cookieArray = array('remember_me'=>$requestData['remember_me'],'guest'=>true,'email'=>$requestData['username']);
			$this->rememberMe->set(json_encode($cookieArray));
		}
		if(isset($requestData['username'])){
			$websiteId = (int)$this->storeManager->getWebsite()->getId();
			$isEmailNotExists = $this->customerAccountManagement->isEmailAvailable($requestData['username']);
			if($isEmailNotExists){
				$quoteId = $this->checkoutSession->getQuote()->getId();
				$quote = $this->quoteFactory->create()->load($quoteId);
				$quote->setCustomerEmail($requestData['username']);
				$quote->setCustomerIsGuest(true);
				$quote->save();
				$result->setData(['success' => true, 'url' => $checkoutUrl]);

			}else{
				$message =  __('You already have an account with us. Please <a href="%1">sign in here</a>', $signUrl);
				$result->setData(['success' => false, 'message' => $message]);
			}
		}else{
			$message =  __('Enter your email id');
			$result->setData(['success' => false, 'message' => $message]);
		}
		
		return $result;
    }
}
