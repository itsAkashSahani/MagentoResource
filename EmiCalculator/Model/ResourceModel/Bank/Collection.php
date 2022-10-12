<?php
namespace Ambab\EmiCalculator\Model\ResourceModel\Bank;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'bank_id';
	
	protected $_eventPrefix = 'ambab_emicalculator_bank_collection';

    protected $_eventObject = 'bank_collection';
	
	/**
     * Define model & resource model
     */
	protected function _construct()
	{
		$this->_init('Ambab\EmiCalculator\Model\Bank', 'Ambab\EmiCalculator\Model\ResourceModel\Bank');
	}
}
?>
