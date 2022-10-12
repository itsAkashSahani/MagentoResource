<?php

namespace Albatool\StoreLocator\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use \Magento\Inventory\Model\ResourceModel\Source\CollectionFactory as InventoryCollectionFactory;

/**
 * Class Data
 * @package RDewan\OrderExport\Helper
 */
class Data extends AbstractHelper
{
    const CONFIG_MODULE_PATH = 'albatool_storelocator';
    const MODULE_ENABLE = 'enable_module';
    const GOOGLE_API_KEY = 'albatool_checkout\module\google_api_key';
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
     * Data constructor.
     *
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param ManagerInterface $messageManager
     * @param EncryptorInterface $_encryptor
     * @param InventoryCollectionFactory $inventoryCollectionFactory,
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        ManagerInterface $messageManager,
        EncryptorInterface $_encryptor,
        InventoryCollectionFactory $inventoryCollectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->messageManager = $messageManager;
        $this->_encryptor = $_encryptor;
        $this->inventoryCollectionFactory = $inventoryCollectionFactory;
        parent::__construct($context);
    }
    
    /**
     * Get google api key
     * 
     * @return string
     */
    public function getGoogleApiKey()
    {
        return $this->scopeConfig->getValue(self::GOOGLE_API_KEY, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, null);

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
}
