<?php

namespace Albatool\CustomSortBy\Model\Rewrite\ResourceModel\Fulltext\Collection;

use Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection\SearchResultApplierInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection;

class SearchResultApplier extends \Magento\Elasticsearch\Model\ResourceModel\Fulltext\Collection\SearchResultApplier
{
    protected $request;

    private $collection;
    private $searchResult;
    private $size;
    private $currentPage;
    
    public function __construct(
        Collection $collection,
        SearchResultInterface $searchResult,
        int $size,
        int $currentPage,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->collection = $collection;
        $this->searchResult = $searchResult;
        $this->size = $size;
        $this->currentPage = $currentPage;
        $this->request = $request;
    }

    public function apply()
    {
        if (empty($this->searchResult->getItems())) {
            $this->collection->getSelect()->where('NULL');

            return;
        }

        $items = $this->sliceItems($this->searchResult->getItems(), $this->size, $this->currentPage);
        $ids = [];
        foreach ($items as $item) {
            $ids[] = (int)$item->getId();
        }
        $this->collection->getSelect()
            ->where('e.entity_id IN (?)', $ids)
            ->reset(\Magento\Framework\DB\Select::ORDER);
        $sortOrder = $this->searchResult->getSearchCriteria()
            ->getSortOrders();
        if (!empty($sortOrder['price']) && $this->collection->getLimitationFilters()->isUsingPriceIndex()) {
            $sortDirection = $sortOrder['price'];
            $this->collection->getSelect()
                ->order(
                    new \Zend_Db_Expr("price_index.min_price = 0, price_index.min_price {$sortDirection}")
                );
        } else {
             if(!empty($this->request->getParams('product_list_order'))){
                if($this->request->getParam('product_list_order') == 'low_to_high'){
                    $sortDirection = 'asc';
                    $this->collection->getSelect()
                        ->order(new \Zend_Db_Expr("price_index.min_price = 0, price_index.min_price {$sortDirection}"));
                }
                else if($this->request->getParam('product_list_order') == 'high_to_low'){
                    $sortDirection = 'desc';
                    $this->collection->getSelect()
                        ->order(new \Zend_Db_Expr("price_index.min_price = 0, price_index.min_price {$sortDirection}"));
                }
                else if($this->request->getParam('product_list_order') == 'whats_new'){
                    $sortDirection = 'desc';
                    $this->collection->getSelect()
                        ->order(new \Zend_Db_Expr("created_at {$sortDirection}"));
                }

                else if($this->request->getParam('product_list_order') == 'by_discount'){
                    $sortDirection = 'asc';
                    $this->collection->getSelect()
                    ->columns(
                        array('discount' => '((price_index.final_price * 100)/price_index.price)')
                    )
                    ->group('e.entity_id')
                    ->order('discount ' . $sortDirection);
                }
             }
             else{
                $orderList = join(',', $ids);
                $this->collection->getSelect()
                ->order(new \Zend_Db_Expr("FIELD(e.entity_id,$orderList)"));
            }
        }
    }

    private function sliceItems(array $items, int $size, int $currentPage): array
    {
        if ($size !== 0) {
            // Check that current page is in a range of allowed page numbers, based on items count and items per page,
            // than calculate offset for slicing items array.
            $itemsCount = count($items);
            $maxAllowedPageNumber = ceil($itemsCount/$size);
            if ($currentPage < 1) {
                $currentPage = 1;
            }
            if ($currentPage > $maxAllowedPageNumber) {
                $currentPage = $maxAllowedPageNumber;
            }

            $offset = $this->getOffset($currentPage, $size);
            $items = array_slice($items, $offset, $size);
        }

        return $items;
    }

    private function getOffset(int $pageNumber, int $pageSize): int
    {
        return ($pageNumber - 1) * $pageSize;
    }
}