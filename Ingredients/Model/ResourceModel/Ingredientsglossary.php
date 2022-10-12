<?php
namespace Albatool\Ingredients\Model\ResourceModel;

class Ingredientsglossary extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('ingredientsglossary', 'ingredientsglossary_id');
    }
}
?>