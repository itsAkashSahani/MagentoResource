<?php
namespace Albatool\SkinQuiz\Block\Adminhtml\Skinquiz\Edit;

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
        $this->setId('skinquiz_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Skinquiz Information'));
    }
}