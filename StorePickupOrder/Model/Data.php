<?php
namespace Albatool\StorePickupOrder\Model;

use Magento\Framework\Model\AbstractModel;

    class Data extends AbstractModel
    {   
        protected function _construct()
        {
            $this->_init('Albatool\StorePickupOrder\Model\ResourceModel\Data');
        }
    }