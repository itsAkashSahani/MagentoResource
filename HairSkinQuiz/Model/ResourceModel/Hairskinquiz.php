<?php
namespace Albatool\HairSkinQuiz\Model\ResourceModel;

class Hairskinquiz extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('hair_skin_quiz', 'id');
    }
}
?>