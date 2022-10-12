<?php
  
namespace Albatool\StorePickupOrder\Model\ResourceModel\Sample;
  
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Albatool\StorePickupOrder\Model\Sample as Model;
use Albatool\StorePickupOrder\Model\ResourceModel\Sample as ResourceModel;
  
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Albatool\StorePickupOrder\Model\Sample',
            'Albatool\StorePickupOrder\Model\ResourceModel\Sample'
        );
    }
}
