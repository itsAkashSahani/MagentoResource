<?php
namespace Albatool\HairSkinQuiz\Model;

class Hairskinquiz extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Albatool\HairSkinQuiz\Model\ResourceModel\Hairskinquiz');
    }
}
?>