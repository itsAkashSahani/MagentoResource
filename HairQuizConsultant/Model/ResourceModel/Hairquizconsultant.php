<?php
namespace Albatool\HairQuizConsultant\Model\ResourceModel;

class Hairquizconsultant extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('hair_quiz_consultant', 'id');
    }
}
?>