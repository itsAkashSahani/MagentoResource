<?php
namespace Albatool\CustomSortBy\Plugin\Catalog\Block;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{

    /**
     * Set collection to pager
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;

        $this->_collection->setCurPage($this->getCurrentPage());

        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }
        if ($this->getCurrentOrder()) {
            if (($this->getCurrentOrder()) == 'position') {
                $this->_collection->addAttributeToSort(
                    $this->getCurrentOrder(),
                    $this->getCurrentDirection()
                );
            } else {
                if ($this->getCurrentOrder() == 'high_to_low') {
                    $this->_collection->setOrder('price', 'desc');
                } elseif ($this->getCurrentOrder() == 'low_to_high') {
                    $this->_collection->setOrder('price', 'asc');
                }
                elseif ($this->getCurrentOrder() == 'whats_new') {
                    $this->_collection->setOrder('created_at', 'asc');
                }
                elseif ($this->getCurrentOrder() == 'by_discount') {
                        $this->_collection->getSelect()
                    ->columns(
                        array('discount' => '((price_index.final_price * 100)/price_index.price)')
                    )
                    ->group('e.entity_id')
                    ->order('discount ' . 'asc');
                }

            }
        }
        return $this;
    }

}
