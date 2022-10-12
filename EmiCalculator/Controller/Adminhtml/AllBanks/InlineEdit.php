<?php
namespace Ambab\EmiCalculator\Controller\Adminhtml\AllBanks;

use Magento\Backend\App\Action\Context;
use Ambab\EmiCalculator\Api\BankRepositoryInterface as BankRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Ambab\EmiCalculator\Api\Data\BankInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $BankRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        Context $context,
        BankRepository $BankRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->BankRepository = $BankRepository;
        $this->jsonFactory = $jsonFactory;
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

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $bankId) {
            $bank = $this->BankRepository->getById($bankId);
            try {
                $bankData = $postItems[$bankId];
                $extendedBankData = $bank->getData();
                $this->setbankData($bank, $extendedBankData, $bankData);
                $this->BankRepository->save($bank);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithbankId($bank, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithbankId($bank, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithbankId(
                    $bank,
                    __('Something went wrong while saving the Bank.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithbankId(BankInterface $bank, $errorText)
    {
        return '[Bank ID: ' . $bank->getId() . '] ' . $errorText;
    }

    public function setbankData(\Ambab\EmiCalculator\Model\Bank $bank, array $extendedBankData, array $bankData)
    {
        $bank->setData(array_merge($bank->getData(), $extendedBankData, $bankData));
        return $this;
    }
}
?>
