<?php
namespace Albatool\StorePickupOrder\Model\ResourceModel\Sms;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Albatool\StorePickupOrder\Model\Sms',
            'Albatool\StorePickupOrder\Model\ResourceModel\Sms'
        );
    }
}