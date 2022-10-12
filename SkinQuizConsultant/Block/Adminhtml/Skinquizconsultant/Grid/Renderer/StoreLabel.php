<?php
namespace Albatool\SkinQuizConsultant\Block\Adminhtml\Skinquizconsultant\Grid\Renderer;

use Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer;
use Magento\Framework\DataObject;
use Magento\Store\Model\StoreManagerInterface;

class StoreLabel extends AbstractRenderer
{
    private $_storeManager;
    protected $storeList;
    /**
        * @param \Magento\Backend\Block\Context $context
        * @param array $data
        */
    public function __construct(
        \Magento\Backend\Block\Context $context, 
        StoreManagerInterface $storemanager,
        \Albatool\SkinQuizConsultant\Model\StoreList $storeList, 
        array $data = []
    )
    {
        $this->_storeManager = $storemanager;
        $this->storeList = $storeList;
        parent::__construct($context, $data);
        $this->_authorization = $context->getAuthorization();
    }
   /**
    * Renders grid column
    *
    * @param DataObject $row
    * @return  string
    */
    public function render(DataObject $row)
    {
        return $this->storeList->getOptionText($this->_getValue($row));
    }
}