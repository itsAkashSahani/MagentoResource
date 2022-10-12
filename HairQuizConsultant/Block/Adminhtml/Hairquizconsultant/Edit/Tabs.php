<?php
namespace Albatool\HairQuizConsultant\Block\Adminhtml\Hairquizconsultant\Edit;

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
        $this->setId('hairquizconsultant_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Hairquizconsultant Information'));
    }
}