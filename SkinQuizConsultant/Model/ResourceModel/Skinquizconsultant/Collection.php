<?php

namespace Albatool\SkinQuizConsultant\Model\ResourceModel\Skinquizconsultant;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Albatool\SkinQuizConsultant\Model\Skinquizconsultant', 'Albatool\SkinQuizConsultant\Model\ResourceModel\Skinquizconsultant');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>