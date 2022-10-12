<?php
namespace Ambab\EmiCalculator\Controller\Adminhtml\AllBanks;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed('Ambab_EmiCalculator::bank_delete');
	}
	
	/**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('bank_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $bankName = "";
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Ambab\EmiCalculator\Model\Bank::class);
                $model->load($id);
                $bankName = $model->getBankName();
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('The bank has been deleted.'));
                // go to grid
                $this->_eventManager->dispatch(
                    'adminhtml_bank_on_delete',
                    ['bank_name' => $bankName, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_bank_on_delete',
                    ['bank_name' => $bankName, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['bank_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a bank to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
?>
