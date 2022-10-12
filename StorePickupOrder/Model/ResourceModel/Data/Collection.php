<?php 
namespace Albatool\StorePickupOrder\Model\ResourceModel\Data;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
        'Albatool\StorePickupOrder\Model\Data',
        'Albatool\StorePickupOrder\Model\ResourceModel\Data'
    );
    }
}