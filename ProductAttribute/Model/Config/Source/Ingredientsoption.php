<?php
namespace Albatool\ProductAttribute\Model\Config\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Ingredientsoption extends AbstractSource
{
    protected $ingredientsmodelFactory;

    public function __construct(
    \Albatool\Ingredients\Model\IngredientsglossaryFactory $ingredientsmodelFactory
    )
    {
        $this->ingredientsmodelFactory = $ingredientsmodelFactory;
    }
    
    public function getAllOptions()
    {
        $resultPage = $this->ingredientsmodelFactory->create();
        $collection = $resultPage->getCollection();

        $coll_arr = array();
        $i = 1;
        foreach($collection as $coll){
            $coll_arr[] = ['label' => $coll->getName(), 'value' => $i];
            $i++;
        }

        if (null === $this->_options) {
            
                $this->_options = $coll_arr;
            }

        return $this->_options;
    }
}