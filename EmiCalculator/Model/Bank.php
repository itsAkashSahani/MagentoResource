<?php
namespace Ambab\EmiCalculator\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;

use Ambab\EmiCalculator\Api\Data\BankInterface;

class Bank extends AbstractModel implements BankInterface, IdentityInterface
{
	const CACHE_TAG = 'ambab_emicalculator_bank';

	protected $_eventPrefix = 'ambab_emicalculator_bank';
	
	//Unique identifier for use within caching
	protected $_cacheTag = self::CACHE_TAG;
	
	protected function _construct()
    {
        $this->_init('Ambab\EmiCalculator\Model\ResourceModel\Bank');
    }
	
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getBankId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    public function getBankId() 
    {
        return parent::getData(self::BANK_ID);
    }

    public function getBankName() 
    {
        return $this->getData(self::BANK_NAME);
    }

    public function getCreatedAt() 
    {
        return $this->getData(self::CREATED_AT);
    }

    public function getUpdatedAt() 
    {
        return $this->getData(self::UPDATED_AT);
    }


    public function setBankId($id) 
    {
        return parent::setData(self::BANK_ID, $id);
    }

    public function setBankName($bankName) 
    {
        return $this->setData(self::BANK_NAME, $bankName);
    }

    public function setCreatedAt($createdAt) 
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function setUpdatedAt($updatedAt) 
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
?>
