<?php
namespace Albatool\StorePickupOrder\Model\ResourceModel;
class Sms extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('sms_pickup_otp', 'id');   //here "vky_test" is table name and "test_id" is the primary key of custom table
    }
}