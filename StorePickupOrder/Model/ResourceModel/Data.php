<?php

namespace Albatool\StorePickupOrder\Model\ResourceModel;


use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Data extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('inventory_pickup_location_order', 'order_id');  
    }
}