<?php
  
namespace Albatool\StorePickupOrder\Model\ResourceModel;
  
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
  
class Sample extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sms_pickup_otp', 'id');
    }
}