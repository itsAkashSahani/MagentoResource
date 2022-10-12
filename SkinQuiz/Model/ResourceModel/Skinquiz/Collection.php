<?php

namespace Albatool\SkinQuiz\Model\ResourceModel\Skinquiz;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Albatool\SkinQuiz\Model\Skinquiz', 'Albatool\SkinQuiz\Model\ResourceModel\Skinquiz');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>