<?php
namespace Ambab\EmiCalculator\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

use Ambab\EmiCalculator\Model\ResourceModel\Bank as ResourceBank;
use Ambab\EmiCalculator\Model\ResourceModel\Bank\CollectionFactory as BankCollectionFactory;
use Ambab\EmiCalculator\Api\Data;
use Ambab\EmiCalculator\Api\BankRepositoryInterface;


class BankRepository implements BankRepositoryInterface
{
    protected $resource;

    protected $bankFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataBankFactory;

    private $storeManager;

    public function __construct(
        ResourceBank $resource,
        BankFactory $bankFactory,
        BankRepositoryInterface $dataBankFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->bankFactory = $bankFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBankFactory = $dataBankFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }


    public function save(\Ambab\EmiCalculator\Api\Data\BankInterface $bank)
    {
        if ($bank->getStoreId() === null) {
            $storeId = $this->storeManager->getStore()->getBankId();
            $bank->setStoreId($storeId);
        }
        try {
            $this->resource->save($bank);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the bank: %1', $exception->getMessage()),
                $exception
            );
        }
        return $bank;
    }

    public function getById($bankId)
    {
		$bank = $this->bankFactory->create();
        $bank->load($bankId);
        if (!$bank->getBankId()) {
            throw new NoSuchEntityException(__('Bank with id "%1" does not exist.', $bankId));
        }
        return $bank;
    }

    public function delete(\Ambab\EmiCalculator\Api\Data\BankInterface $bank)
    {
        try {
            $this->resource->delete($bank);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the bank: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    public function deleteById($bankId)
    {
        return $this->delete($this->getById($bankId));
    }
}
