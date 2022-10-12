<?php 
namespace Albatool\SkinQuizConsultant\Model\Import; 
use Albatool\Ingredients\Model\Import\IngredientsGlossary\RowValidatorInterface as ValidatorInterface; 
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface; 
use Magento\Framework\App\ResourceConnection; 

class SkinquizConsultant extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity 
{ 
     const ID = 'id'; 
     const SKINQUIZCOMBINATION = 'skin_quiz_combination';
     const SKINQUIZDESCRIPTION = 'skin_quiz_result_description';
     const CONSULTPRODUCTS = 'consult_products';
     const BANNERIMAGE = 'banner_image';
     const MOBILEBANNERIMAGE = 'mobile_banner_image';
     const Q1 = 'skin_quiz_q1';
     const Q2 = 'skin_quiz_q2';
     const Q3 = 'skin_quiz_q3';
     const BEAUTYSTEPS = 'beauty_steps';
     const KIT = 'quiz_kit';
     const STORECODE = 'store_code';
     
     const TABLE_Entity = 'skin_quiz_consultant'; /** * Validation failure message template definitions * * @var array */ 
     protected $_messageTemplates = [ ValidatorInterface::ERROR_TITLE_IS_EMPTY => 'Name is empty',];
 
     protected $_permanentAttributes = [self::ID];
     protected $needColumnCheck = true;
     protected $groupFactory;
     protected $validColumnNames = [
                         self::ID,
                         self::SKINQUIZCOMBINATION,
                         self::SKINQUIZDESCRIPTION,
                         self::CONSULTPRODUCTS,
                         self::BANNERIMAGE,
                         self::MOBILEBANNERIMAGE,
                         self::Q1,
                         self::Q2,
                         self::Q3,
                         self::BEAUTYSTEPS,
                         self::KIT,
                         self::STORECODE
                    ];
     protected $logInHistory = true;
     protected $_validators = [];
     protected $_connection;
     protected $_resource;
    
     public function __construct(
     \Magento\Framework\Json\Helper\Data $jsonHelper,
     \Magento\ImportExport\Helper\Data $importExportData,
     \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
     \Magento\Framework\App\ResourceConnection $resource,
     \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
     \Magento\Framework\Stdlib\StringUtils $string,
     ProcessingErrorAggregatorInterface $errorAggregator,
     \Magento\Customer\Model\GroupFactory $groupFactory)
     {
          $this->jsonHelper = $jsonHelper;
    	  $this->_importExportData = $importExportData;
    	  $this->_resourceHelper = $resourceHelper;
    	  $this->_dataSourceModel = $importData;
    	  $this->_resource = $resource;
    	  $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    	  $this->errorAggregator = $errorAggregator;
    	  $this->groupFactory = $groupFactory;
     }
 
     public function getValidColumnNames()
     {
          return $this->validColumnNames;
     }
 
     public function getEntityTypeCode()
     {
          return 'skinquiz_consultant';
     }
 
     public function validateRow(array $rowData, $rowNum)
     {
          if (isset($this->_validatedRows[$rowNum]))
          {
       	       return !$this->getErrorAggregator()->isRowInvalid($rowNum);
          }
          $this->_validatedRows[$rowNum] = true;
          return !$this->getErrorAggregator()->isRowInvalid($rowNum);
     }

     protected function _importData()
     {
          $this->saveEntity();
          return true;
     }
    
     public function saveEntity()
     {
          $this->saveAndReplaceEntity();
          return $this;
     }
 
     public function replaceEntity()
     {
          $this->saveAndReplaceEntity();
          return $this;
     }
    
     public function deleteEntity()
     {
          $listTitle = [];
          while ($bunch = $this->_dataSourceModel->getNextBunch())
          {
               foreach ($bunch as $rowNum => $rowData)
               {
                    $this->validateRow($rowData, $rowNum);
                    if (!$this->getErrorAggregator()->isRowInvalid($rowNum))
	                   {
                         $rowTtile = $rowData[self::ID];
                         $listTitle[] = $rowTtile;
                    }
                    if ($this->getErrorAggregator()->hasToBeTerminated())
 	                   {
                         $this->getErrorAggregator()->addRowToSkip($rowNum);
                    }
               }
          }
          if ($listTitle)
          {
               $this->deleteEntityFinish(array_unique($listTitle),self::TABLE_Entity);
          }
          return $this;
     }
     
     protected function saveAndReplaceEntity()
     {
          $behavior = $this->getBehavior();
          $listTitle = [];
          while ($bunch = $this->_dataSourceModel->getNextBunch())
          {
               $entityList = [];
               foreach ($bunch as $rowNum => $rowData)
	              {
                    if (!$this->validateRow($rowData, $rowNum))
	                   {
                         $this->addRowError(ValidatorInterface::ERROR_TITLE_IS_EMPTY, $rowNum);
                         continue;
                    }
                    if ($this->getErrorAggregator()->hasToBeTerminated())
	                   {
                         $this->getErrorAggregator()->addRowToSkip($rowNum);
                         continue;
                    }
 
                    $rowTtile= $rowData[self::ID];

                    $combination = $rowData[self::Q1] . "::" . $rowData[self::Q2] . "::" . $rowData[self::Q3];

                    $listTitle[] = $rowTtile;
                    $entityList[$rowTtile][] = [
                    self::ID => $rowData[self::ID],
                    self::SKINQUIZCOMBINATION => $combination,
                    self::SKINQUIZDESCRIPTION => $rowData[self::SKINQUIZDESCRIPTION],
                    self::CONSULTPRODUCTS => $rowData[self::CONSULTPRODUCTS],
                    self::BANNERIMAGE => $rowData[self::BANNERIMAGE],
                    self::MOBILEBANNERIMAGE => $rowData[self::MOBILEBANNERIMAGE],
                    self::Q1 => $rowData[self::Q1],
                    self::Q2 => $rowData[self::Q2],
                    self::Q3 => $rowData[self::Q3],
                    self::BEAUTYSTEPS => $rowData[self::BEAUTYSTEPS],
                    self::KIT => $rowData[self::KIT],
                    self::STORECODE =>$rowData[self::STORECODE]
               ];
               }
               if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior)
	              {
                    if ($listTitle)
	                   {
                         if ($this->deleteEntityFinish(array_unique(  $listTitle), self::TABLE_Entity))
		                        {
                              $this->saveEntityFinish($entityList, self::TABLE_Entity);
                         }
                    }
               }
	              elseif (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior)
	              {
                    $this->saveEntityFinish($entityList, self::TABLE_Entity);
               }
          }
          return $this;
     }
 
     protected function saveEntityFinish(array $entityData, $table)
     {
          if ($entityData)
          {
               $tableName = $this->_connection->getTableName($table);
               $entityIn = [];
               foreach ($entityData as $id => $entityRows)
	              {
                    foreach ($entityRows as $row)
		                   {
                         $entityIn[] = $row;
                    }
               }
               if ($entityIn)
	              {
                    $this->_connection->insertOnDuplicate($tableName, $entityIn,[
                    self::ID,
                    self::SKINQUIZCOMBINATION,
                    self::SKINQUIZDESCRIPTION,
                    self::CONSULTPRODUCTS,
                    self::BANNERIMAGE,
                    self::MOBILEBANNERIMAGE,
                    self::Q1,
                    self::Q2,
                    self::Q3,
                    self::BEAUTYSTEPS,
                    self::KIT,
                    self::STORECODE
               ]);
               }
          }
          return $this;
     }
 
     protected function deleteEntityFinish(array $ids, $table)
     {
          if ($table && $listTitle)
          {
               try
	              {
                    $this->countItemsDeleted += $this->_connection->delete(
                    $this->_connection->getTableName($table),
                    $this->_connection->quoteInto('id IN (?)', $ids));
                    return true;
               }
	              catch (\Exception $e)
	              {
                    return false;
               }
          } 
          else
          {
               return false;
          }
     }
}