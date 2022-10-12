<?php

namespace Albatool\Checkout\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use \Magento\Inventory\Model\ResourceModel\Source\CollectionFactory as InventoryCollectionFactory;
use Albatool\Checkout\Model\Cookie\RememberMe;

/**
 * Class Data
 * @package RDewan\OrderExport\Helper
 */
class Data extends AbstractHelper
{
    const CONFIG_MODULE_PATH = 'albatool_checkout';
    const MODULE_ENABLE = 'enable_module';
    const GOOGLE_API_KEY = 'google_api_key';
    const SHOW_PICKUP = 'enable_pickup';
	const CALL_STATIC_BLOCK_GUEST = 'call_static_block_guest';
    const CALL_STATIC_BLOCK_CUSTOMER = 'call_static_block_customer';
    const MODULE_GROUP = 'module';


	/**
	* @var ScopeConfigInterface
	*/
	protected $scopeConfig;
	
	/**
     * @var ManagerInterface
     */
    protected $messageManager;
	
	/**
     * @var EncryptorInterface
     */
	protected $_encryptor;
	
	/**
     * @var InventoryCollectionFactory
     */
	protected $inventoryCollectionFactory;
	
	/**
    * @var RememberMe
    */
	protected $rememberMe;
	
    /**
     * Data constructor.
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param ManagerInterface $messageManager
     * @param EncryptorInterface $_encryptor
     * @param InventoryCollectionFactory $inventoryCollectionFactory,
	 * @param RememberMe $rememberMe
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
		ManagerInterface $messageManager,
		EncryptorInterface $_encryptor,
		InventoryCollectionFactory $inventoryCollectionFactory,
		RememberMe $rememberMe
	) {
	    $this->scopeConfig = $scopeConfig;
		$this->messageManager = $messageManager;
		$this->_encryptor = $_encryptor;
		$this->inventoryCollectionFactory = $inventoryCollectionFactory;
		$this->rememberMe = $rememberMe;
        parent::__construct($context);
    }

    /**
     * @param string $fieldName
     * @param string $group
     * @return string
     */
    public function getRequestConfig($fieldName = '',$group = '')
    {
        $fieldName = ($fieldName !== '') ? '/' . $fieldName : '';
        $group = ($group !== '') ? '/' . $group : '';

        return $this->scopeConfig->getValue(self::CONFIG_MODULE_PATH . $group . $fieldName,  \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
	
	/**
     * Get google api key
     * 
     * @return string
     */
    public function getGoogleApiKey()
    {
		$googleApiKey = $this->getRequestConfig(self::GOOGLE_API_KEY, self::MODULE_GROUP);
		return $this->_encryptor->decrypt($googleApiKey);
    }

    /**
     * Get google api key
     * 
     * @return string
     */
    public function isPickupStoreEnabled()
    {
		return $this->getRequestConfig(self::SHOW_PICKUP, self::MODULE_GROUP);
    }
	
	/**
     * Get Inventory Source Collection
     * 
     * @return array
     */
    public function getInventorySourceCollection()
    {
		$sourceListArr = $this->inventoryCollectionFactory->create();
		
		return $sourceListArr->getData();
    }
	
	/**
     * Get static block id for login page
     * 
     * @return string
     */
    public function getStaticBlockCustomer()
    {
		return $this->getRequestConfig(self::CALL_STATIC_BLOCK_CUSTOMER, self::MODULE_GROUP);
    }
	
	/**
     * Get static block id for guest login page
     * 
     * @return string
     */
    public function getStaticBlockGuest()
    {
		return $this->getRequestConfig(self::CALL_STATIC_BLOCK_GUEST, self::MODULE_GROUP);
    }
	
	/**
     * Get cookie value
     * 
     * @return array
     */
    public function getCookieRememberMe()
    {
		$rememberMe = array();
		if($this->rememberMe->get()){
			$rememberMe = json_decode($this->rememberMe->get(),true);
		}
		return  $rememberMe;
    }

    /**
     * Prepare telephone field config according to the Magento default config
     * @param $addressType
     * @param string $method
     * @return array
     */
    public function telephoneFieldConfig($addressType, $method = '')
    {
        return  [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => $addressType . $method,
                'customEntry' => null,
                'template' => 'ui/form/field',
                'elementTmpl' => 'Albatool_Checkout/form/element/telephone',
                'tooltip' => [
                    'description' => 'For delivery questions.',
                    'tooltipTpl' => 'ui/form/element/helper/tooltip'
                ],
            ],
            'dataScope' => $addressType . $method . '.telephone',
            'dataScopePrefix' => $addressType . $method,
            'label' => __('Phone Number'),
            'provider' => 'checkoutProvider',
            'sortOrder' => 120,
            'validation' => [
                "required-entry"    => true,
                "max_text_length"   => 255,
                "min_text_length"   => 1
            ],
            'options' => [],
            'filterBy' => null,
            'customEntry' => null,
            'visible' => true,
            'focused' => false,
        ];
    }
}
