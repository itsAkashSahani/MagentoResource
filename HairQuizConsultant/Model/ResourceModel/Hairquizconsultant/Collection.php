<?php

namespace Albatool\HairQuizConsultant\Model\ResourceModel\Hairquizconsultant;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Albatool\HairQuizConsultant\Model\Hairquizconsultant', 'Albatool\HairQuizConsultant\Model\ResourceModel\Hairquizconsultant');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>