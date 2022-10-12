<?php

namespace Albatool\Ingredients\Block;

class IngredientsGlossary extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Albatool\Ingredients\Model\IngredientsglossaryFactory
     */
    private $ingredientsGlossaryFactory;

    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $json;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Albatool\Ingredients\Model\IngredientsglossaryFactory $ingredientsGlossaryFactory,
        \Magento\Framework\Serialize\Serializer\Json $json,
        array $data = []
    ) {
        $this->ingredientsGlossaryFactory = $ingredientsGlossaryFactory;
        $this->json = $json;
        parent::__construct($context, $data);
    }

    public function getIndexData()
    {
        $collection = $this->ingredientsGlossaryFactory->create()->getCollection();
        $collection->addFieldToSelect(['shortcharacter']);
        $collection->setOrder('shortcharacter', 'ASC');

        $collection->getSelect()->group('shortcharacter');
        return $collection;
    }

    public function getJsonData()
    {
        $collection = $this->ingredientsGlossaryFactory->create()->getCollection();
        //$collection->addFieldToSelect(['shortcharacter']);
        //$collection->setOrder('shortcharacter', 'ASC');

        $result = $collection->getData();

        $jsonDecode = json_encode($result);
        //$jsonDecode = $this->json->unserialize($result);
        return $jsonDecode;
    }

    public function getGlossaryData()
    {
        $collection = $this->ingredientsGlossaryFactory->create()->getCollection();
        $collection->setOrder('name', 'ASC');

        $glossaryMainArray = [];
        foreach ($collection as $key => $items) {
            $a = [
                'ingredientsglossary_id' => $items->getIngredientsglossaryId(),
                'name' => $items->getName(),
                'shortcharacter' => $items->getShortcharacter(),
                'description' => $items->getDescription(),
                'slug' => $items->getSlug(),
                'type' => $items->getType(),
                'is_organic' => $items->getIsOrganic()
            ];

            $glossaryMainArray[$items->getShortcharacter()][] = $a;
        }

        return $glossaryMainArray;
    }

    public function getIngredientInfo($ingId)
    {
        $collection = $this->ingredientsGlossaryFactory->create()->getCollection();
        $collection->addFieldToFilter('ingredientsglossary_id', ['eq' => $ingId]);

        $ingredientData = $collection->getData();

        return $ingredientData;
    }
}
