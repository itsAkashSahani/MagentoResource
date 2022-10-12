<?php 
namespace Albatool\HairQuizConsultant\Model\Import; 
use Albatool\Ingredients\Model\Import\IngredientsGlossary\RowValidatorInterface as ValidatorInterface; 
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface; 
use Magento\Framework\App\ResourceConnection; 

class HairquizConsultant extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity 
{ 
     const ID = 'id'; 
     const HAIRQUIZCOMBINATION = 'hair_quiz_combination';
     const HAIRQUIZDESCRIPTION = 'hair_quiz_result_description';
     const BANNERIMAGE = 'banner_image';
     const MOBILEBANNERIMAGE = 'mobile_banner_image';
     const CONSULTPRODUCTSHAIR = 'consult_products_hair';
     const Q1 = 'hair_quiz_q1';
     const Q2 = 'hair_quiz_q2';
     const Q3 = 'hair_quiz_q3';
     const Q4 = 'hair_quiz_q4';
     const Q5 = 'hair_quiz_q5';
     const BEAUTYSTEPS = 'beauty_steps';
     const KIT = 'quiz_kit';
     const STORECODE = 'store_code';

     const TABLE_Entity = 'hair_quiz_consultant'; 
     
     protected $_messageTemplates = [ 
          ValidatorInterface::ERROR_TITLE_IS_EMPTY => 'Something Went Wrong',
     ];
 
     protected $_permanentAttributes = [self::ID];
     protected $needColumnCheck = true;
     protected $groupFactory;
     protected $validColumnNames = [
                              self::ID, 
                              self::HAIRQUIZCOMBINATION, 
                              self::HAIRQUIZDESCRIPTION,
                              self::BANNERIMAGE,
                              self::MOBILEBANNERIMAGE,
                              self::Q3,
                              self::Q4,
                              self::Q5,
                              self::BEAUTYSTEPS,
                              self::KIT,
                              self::STORECODE,
                              self::CONSULTPRODUCTSHAIR
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
          \Magento\Customer\Model\GroupFactory $groupFactory
     )
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
          return 'hairquiz_consultant';
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

                    $combination = $rowData[self::Q3] . "::" . $rowData[self::Q4] . "::" . $rowData[self::Q5];

                    $listTitle[] = $rowTtile;
                    $entityList[$rowTtile][] = [
                         self::ID => $rowData[self::ID],
                         self::HAIRQUIZDESCRIPTION => $rowData[self::HAIRQUIZDESCRIPTION],
                         self::BANNERIMAGE => $rowData[self::BANNERIMAGE],
                         self::MOBILEBANNERIMAGE => $rowData[self::MOBILEBANNERIMAGE],
                         self::Q3 => $rowData[self::Q3],
                         self::Q4 => $rowData[self::Q4],
                         self::Q5 => $rowData[self::Q5],
                         self::HAIRQUIZCOMBINATION => $combination,
                         self::BEAUTYSTEPS => $rowData[self::BEAUTYSTEPS],
                         self::KIT => $rowData[self::KIT],
                         self::STORECODE => $rowData[self::STORECODE],
                         self::CONSULTPRODUCTSHAIR => $rowData[self::CONSULTPRODUCTSHAIR]
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
                    self::HAIRQUIZCOMBINATION,
                    self::HAIRQUIZDESCRIPTION,
                    self::BANNERIMAGE,
                    self::MOBILEBANNERIMAGE,
                    self::Q3,
                    self::Q4,
                    self::Q5,
                    self::BEAUTYSTEPS,
                    self::KIT,
                    self::STORECODE,
                    self::CONSULTPRODUCTSHAIR
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