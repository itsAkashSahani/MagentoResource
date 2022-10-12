<?php
declare(strict_types=1);

namespace Albatool\ProductAttributeUpdate\Setup\Patch\Data;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Module\Dir;

class OptionAttribute implements DataPatchInterface
{
 private $moduleDataSetup;
 private $eavSetupFactory;
 public $_storeManager;
 private $logger;
 protected $_dir;
 const TYPE_BOOLEAN = "boolean";
 const TYPE_TEXT = "text";
 const TYPE_SELECT = "select";
 protected $_attributeFactory;
 protected $_eavAttribute;
  public function __construct(
    ModuleDataSetupInterface $moduleDataSetup,
    EavSetupFactory $eavSetupFactory,
    \Psr\Log\LoggerInterface $logger,
    \Magento\Store\Model\StoreManagerInterface $storeManager,
    Dir $dir,
    \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeFactory,
    \Magento\Eav\Model\Entity\Attribute  $eavAttribute
) {
    $this->moduleDataSetup = $moduleDataSetup;
    $this->eavSetupFactory = $eavSetupFactory;
    $this->_storeManager = $storeManager;
    $this->logger = $logger;  
    $this->_dir = $dir;
    $this->_attributeFactory = $attributeFactory;
    $this->_eavAttribute = $eavAttribute;
}

/**
 * {@inheritdoc}
 */
public function apply()
{
    /** @var EavSetup $eavSetup */
    $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

    $fileName = 'albatooloption.csv';
    //$this->_dir->getPath('pub');
    $pubPath = $this->_dir->getDir('Albatool_ProductAttributeUpdate', Dir::MODULE_SETUP_DIR) . "/CsvFiles/albatooloption.csv";
    
    $attributeFile = $pubPath;
    $arrResult = array();
    if(($handle = fopen($attributeFile, 'r')) !== FALSE) {
        $row = 1;
        while(($data = fgetcsv($handle, 1000000, ',')) !== FALSE) {
            $col_count = count($data);
            $arrResult[] = $data;               
            $row++;
        }
        fclose($handle);
    } 
    $i = 0;
    foreach ($arrResult as $line) {
        if($i > 0){
            $attributeCode = $line[0];                    
            $attributeName = $line[1];
            $inputType = strtolower($line[2]);
            $options = $line[3];
            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);

           // $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
            switch ($inputType) {
                case ($inputType == self::TYPE_BOOLEAN && isset($attributeCode)):  
                    $this->createBooleanAttribute($eavSetup,$attributeCode,$attributeName,$inputType);
                    break;
                case ($inputType == self::TYPE_SELECT && isset($attributeCode)):
                    $this->createSelectAttribute($eavSetup,$attributeCode,$attributeName,$inputType);
                    if(isset($options) && $options != '' ){
                       $optionName = explode (",", $options);
                        if(count($optionName) > 0){
                             $this->createAttributeOptions($eavSetup,$attributeCode,$optionName);
                        } 
                    }


                case ($inputType == self::TYPE_TEXT && isset($attributeCode)):
                    $this->createTextAttribute($eavSetup,$attributeCode,$attributeName,$inputType);
                    break;
                default:
                    $this->createTextAttribute($eavSetup,$attributeCode,$attributeName,$inputType);                    
            }
        }
        $i++;
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

public function createSelectAttribute($eavSetup,$attributeCode,$attributeName,$inputType){ //,$options
$this->logger->info('-select Attribute---');
    $type = 'text';
    $eavSetup->addAttribute(
    \Magento\Catalog\Model\Product::ENTITY,
        $attributeCode,
        [
            'type' => $type,
            'group' => 'Albatool Attributes',
            'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            'frontend' => '',
            'label' => $attributeName,
            'input' => $inputType,
            'class' => '',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Table',
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'searchable' => false,
            'filterable' => true,
            "filterable_in_search" => 1,
            'comparable' => false,
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'unique' => false , 
            'system' => 1                    
        ]
    );
}

public function createTextAttribute($eavSetup,$attributeCode,$attributeName,$inputType){
    $type = 'text';
    $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        $attributeCode,
        [
            'type' => $type,
            'group' => 'Albatool Attributes',
            'attribute_set' =>  'Default',
            'label' => $attributeName,
            'backend' => '',
            'input' => $inputType,
            'wysiwyg_enabled'   => false,
            'source' => '',
            'required' => false,
            'sort_order' => 3,
            'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            'used_in_product_listing' => true,
            'visible_on_front' => true
        ]
    );
}
public function createAttributeOptions($eavSetup,$attributeCode,$optionName){
      $entityType = 'catalog_product';
      $attributeInfo = $this->_eavAttribute->loadByCode($entityType, $attributeCode);

      $attributeId = $attributeInfo->getAttributeId();
      //$attribute_arr = ['aaa','bbb','ccc','ddd'];
      $attribute_arr = $optionName;

      $option = array();
      $option['attribute_id'] = $attributeId;
      foreach($attribute_arr as $key => $value){
          $option['value'][$value][0]=$value;
          foreach($this->_storeManager as $store){
              $option['value'][$value][$store->getId()] = $value;
          }
      }
      if ($option) {
        $eavSetup->addAttributeOption($option);
      }
  }
}