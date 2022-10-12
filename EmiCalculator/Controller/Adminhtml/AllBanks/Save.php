<?php
namespace Ambab\EmiCalculator\Controller\Adminhtml\AllBanks;

use Magento\Backend\App\Action;
use Ambab\EmiCalculator\Model\Bank;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    protected $dataPersistor;

    private $bankFactory;

    /**
     * @var \Ambab\EmiCalculator\Api\BankRepositoryInterface
     */
    private $bankRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param Ambab\EmiCalculator\Model\BankFactory $bankFactory
     * @param Ambab\EmiCalculator\Api\BankRepositoryInterface $bankRepository
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Ambab\EmiCalculator\Model\BankFactory $bankFactory = null,
        \Ambab\EmiCalculator\Api\BankRepositoryInterface $bankRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->bankFactory = $bankFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(Ambab\EmiCalculator\Model\BankFactory::class);
        $this->bankRepository = $bankRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(Ambab\EmiCalculator\Api\BankRepositoryInterface::class);
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

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (empty($data['bank_id'])) {
                $data['bank_id'] = null;
            }

            /** @var Ambab\EmiCalculator\Model\Bank $model */
            $model = $this->bankFactory->create();

            $id = $this->getRequest()->getParam('bank_id');
            if ($id) {
                try {
                    $model = $this->bankRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This bank no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            $this->_eventManager->dispatch(
                'banks_allbanks_prepare_save',
                ['bank' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->bankRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the bank.'));
                $this->dataPersistor->clear('banks_allbanks');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['bank_id' => $model->getBankId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the bank.'));
            }

            $this->dataPersistor->set('banks_allbanks', $data);
            return $resultRedirect->setPath('*/*/edit', ['bank_id' => $this->getRequest()->getParam('news_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
?>
