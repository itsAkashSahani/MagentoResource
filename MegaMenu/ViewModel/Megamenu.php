<?php
namespace Albatool\MegaMenu\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class Megamenu implements ArgumentInterface
{
    /**
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function getFirstLevelCategories()
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->setStore(0);
        $collection->addAttributeToFilter('include_in_menu', 1);
        $collection->addAttributeToFilter('level', array('eq' => 2));
        return $collection;
    }
}
