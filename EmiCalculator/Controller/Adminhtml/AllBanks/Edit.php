<?php
namespace Ambab\EmiCalculator\Controller\Adminhtml\AllBanks;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;

class Edit extends Action implements HttpGetActionInterface
{
	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }
	
	/**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Ambab_EmiCalculator::save');
	}

    
    protected function _initAction()
    {
        
        // load layout, set active menu and breadcrumbs
        $resultPage = $this->resultPageFactory->create();
        
        $resultPage->setActiveMenu('Ambab_EmiCalculator::all_banks');
            // ->addBreadcrumb(__('Banks'), __('Banks'))
            // ->addBreadcrumb(__('Manage All Banks'), __('Manage All Banks'));
        return $resultPage;
    }

    
    public function execute()
    {
        

        // // 1. Get ID and create model
        $id = $this->getRequest()->getParam('bank_id');
        $model = $this->_objectManager->create(\Ambab\EmiCalculator\Model\Bank::class);
        
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getBankId()) {
                $this->messageManager->addError(__('This bank no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        
        $this->_coreRegistry->register('banks_allbanks', $model);
        
        // 5. Build edit form
        
        // $resultPage = $this->_initAction();

        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Ambab_EmiCalculator::all_banks')
            ->addBreadcrumb(__('Banks'), __('Banks'))
            ->addBreadcrumb(__('Manage All Banks' ), __('Manage All Banks'));

        $resultPage->getConfig()->getTitle()->prepend(__('All Banks'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getBankId() ? $model->getTitle() : __('Add Banks'));
        return $resultPage;
    }
}
?>