<?php
namespace Albatool\SkinQuiz\Model;

class Skinquiz extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Albatool\SkinQuiz\Model\ResourceModel\Skinquiz');
    }
}
?>