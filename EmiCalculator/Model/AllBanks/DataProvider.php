<?php
namespace Ambab\EmiCalculator\Model\AllBanks;

use Ambab\EmiCalculator\Model\ResourceModel\Bank\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestedFieldName,
        CollectionFactory $bankCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $bankCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestedFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    public function prepareMeta(array $meta) {
        return $meta;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        

        foreach ($items as $bank) {
            $this->loadedData[$bank->getBankId()] = $bank->getData();
        }

        $data = $this->dataPersistor->get('banks_allbanks');
        if(!empty($data)) {
            $bank = $this->collection->getNewEmptyItem();
            $bank->setData($data);
            $this->loadedData[$bank->getBankId()] = $bank->getData();
            $this->dataPersistor->clear('banks_allbanks');
        }

        return $data;
    }
}