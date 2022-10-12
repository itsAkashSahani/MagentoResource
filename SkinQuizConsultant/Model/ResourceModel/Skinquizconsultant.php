<?php
namespace Albatool\SkinQuizConsultant\Model\ResourceModel;

class Skinquizconsultant extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('skin_quiz_consultant', 'id');
    }
}
?>