<?php
namespace Albatool\SkinQuiz\Model\ResourceModel;

class Skinquiz extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('skin_quiz', 'id');
    }
}
?>