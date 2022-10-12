<?php
namespace Albatool\Checkout\Block;
class ProductGift extends \Magento\Framework\View\Element\Template
{    
    protected $_categoryCollectionFactory;
    protected $_productFactory;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductFactory $_productFactory,
        array $data = []
        )
        {
            $this->_categoryCollectionFactory = $categoryCollectionFactory;
            $this->_registry = $registry;
            $this->_productFactory = $_productFactory;
            parent::__construct($context, $data);
    }
    
    /**
     * Get category collection
     *
     * @param bool $isActive
     * @param bool|int $level
     * @param bool|string $sortBy
     * @param bool|int $pageSize
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection or array
     */
    public function getCategoryCollection($isActive = true, $level = false, $sortBy = false, $pageSize = false)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');        
                
        // select categories of certain level
        if ($level) {
            $collection->addLevelFilter($level);
        }
        
        // sort categories by some value
        if ($sortBy) {
            $collection->addOrderField($sortBy);
        }
        
        // select certain number of categories
        if ($pageSize) {
            $collection->setPageSize($pageSize); 
        }    
        
        return $collection;
    }
    
    public function getGiftCount($items)
    {   
        $count = 0;
        foreach($items as $item) {
            $categoryArray = [];
            $categoryIds = $this->getCategoryIds($item);
            $categories = $this->getCategories($categoryIds);
        
            foreach ($categories as $category) {
                array_push($categoryArray, trim($category->getName()));
            }

            if(in_array('Trial', $categoryArray) || in_array('First Order (My Gift)', $categoryArray) || in_array('Plus Price', $categoryArray)) {
                $count = $count + 1;
            }
        }

        return $count;
    }

    public function getGiftCountById($items)
    {   
        $count = 0;
        
        foreach($items as $item) {
            $categoryArray = [];

            $sku = $item['extension_attributes']['sku'];
            
            $product = $this->_productFactory->create()->loadByAttribute('sku', $sku);
            $categoryIds = $product->getCategoryIds();

            if(count($categoryIds) > 0) {
                $categories = $this->getCategories($categoryIds);
        
                foreach ($categories as $category) {
                    array_push($categoryArray, trim($category->getName()));
                }
    
                if(in_array('Trial', $categoryArray) || in_array('First Order (My Gift)', $categoryArray) || in_array('Plus Price', $categoryArray)) {
                    $count = $count + 1;
                }
            }
            
        }

        return $count;
    }

    public function getCategoryIds($item)
    {        
        return $item->getProduct()->getCategoryIds();;
    }

    public function getCategories($categoryIds)
    {        
        return $this->getCategoryCollection()->addAttributeToFilter('entity_id', $categoryIds);
    }
}
