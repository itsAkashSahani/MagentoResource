<?php
namespace Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant\Edit;

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
        $this->setId('skinquizconsultant_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Skinquizconsultant Information'));
    }
}