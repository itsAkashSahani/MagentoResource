<?php
namespace Albatool\HairSkinQuiz\Block\Adminhtml\Hairskinquiz\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('hairskinquiz_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Hairskinquiz Information'));
    }
}