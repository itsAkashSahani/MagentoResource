<?php

/**
 * Assign Attribute to Attribute Set.
 * @package   Ambab_Attributes
 * @author    Avinash Gowda 
 */

namespace Albatool\ProductAttributeUpdate\Setup\Patch\Data;

use Magento\Catalog\Model\Product as Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir;


/**
 * Class AddCopierAttributes
 * @package Ambab\Attributes\Setup\Patch\Data
 */
class AddAttributes implements DataPatchInterface
{
    private $attributes = [];
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    private $attributeSetFactory;

    /**
     * @var Csv
     */
    private $fileCsv;

    /**
     * @var Dir
     */
    private $moduleDir;

    /**
     * AddMessageAttributes constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     * @param Csv $fileCsv
     * @param Dir $moduleDir
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        Csv $fileCsv,
        Dir $moduleDir
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->fileCsv = $fileCsv;
        $this->moduleDir = $moduleDir;
    }


    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
       // $attributeSet = $this->attributeSetFactory->create();
        $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        // $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);
        // $data = [
        //     'attribute_set_name' => 'Default',
        //     'entity_type_id' => $entityTypeId,
        //     'sort_order' => 200,
        // ];
        // $attributeSet->setData($data);
        // $attributeSet->validate();
        // $attributeSet->save();
        // $attributeSet->initFromSkeleton($attributeSetId);
        // $attributeSet->save();
        $moduleSetupPath = $this->moduleDir->getDir('Albatool_ProductAttributeUpdate', Dir::MODULE_SETUP_DIR) . "/CsvFiles/attributes.csv";
        $rows = $this->fileCsv->getData($moduleSetupPath);
        $titles = array_slice($rows[0],1);
        foreach (array_slice($rows,1) as $valuesRow) {
            $this->attributes[$valuesRow[0]] = array_combine($titles,array_slice($valuesRow,1));
        }
        foreach ( $this->attributes as $attribute => $attributeInfo ) {
            if ($eavSetup->getAttributeId(Product::ENTITY, $attribute)) {
                $eavSetup->removeAttribute(Product::ENTITY, $attribute);
            }
            $eavSetup->addAttribute(
                Product::ENTITY,
                $attribute,
                $attributeInfo
            );
        }

    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
