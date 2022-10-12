<?php
  
namespace Albatool\StorePickupOrder\Model;
  
use Magento\Framework\Model\AbstractModel;
  
class Sample extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Albatool\StorePickupOrder\Model\ResourceModel\Sample');
    }
}
