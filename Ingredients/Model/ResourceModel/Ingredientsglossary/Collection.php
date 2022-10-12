<?php

namespace Albatool\Ingredients\Model\ResourceModel\Ingredientsglossary;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Albatool\Ingredients\Model\Ingredientsglossary', 'Albatool\Ingredients\Model\ResourceModel\Ingredientsglossary');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>