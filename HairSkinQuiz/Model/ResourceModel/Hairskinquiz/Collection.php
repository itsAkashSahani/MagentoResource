<?php

namespace Albatool\HairSkinQuiz\Model\ResourceModel\Hairskinquiz;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Albatool\HairSkinQuiz\Model\Hairskinquiz', 'Albatool\HairSkinQuiz\Model\ResourceModel\Hairskinquiz');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>