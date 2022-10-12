<?php
 
namespace Albatool\Checkout\Plugin;
 
class SidebarPlugin
{    
	/**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $_storeManager;
	
	/**
     * @var \Magento\Framework\App\Http\Context
     */
    private $httpContext;
	
	/**
     * Constructor
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\App\Http\Context $httpContext
	) {
		$this->_storeManager=$storeManager;
		$this->httpContext = $httpContext;
	}
   
    public function afterGetCheckoutUrl(\Magento\Checkout\Block\Onepage\Link $subject, $result)
    {
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $returnurl = $baseUrl . 'customer/account/signin'; // defined custom url for sidebar checkout
		$isLoggedIn = $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
		if($isLoggedIn){
			return $result;
		}
        return $returnurl;
    }        
}