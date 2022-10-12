<?php
namespace Albatool\Ingredients\Block\Adminhtml\Ingredientsglossary\Edit;

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
        $this->setId('ingredientsglossary_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Ingredientsglossary Information'));
    }
}