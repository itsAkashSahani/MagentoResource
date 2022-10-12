<?php 
namespace Albatool\CityDropList\Model\Import; 
use Albatool\CityDropList\Model\Import\CityImport\RowValidatorInterface as ValidatorInterface; 
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface; 
use Magento\Framework\App\ResourceConnection; 

class CityImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity 
{ 
     const ID = 'citydroplist_id'; const NAME = 'city'; const DESC = 'region_id'; const TABLE_Entity = 'albatool_citydroplist'; /** * Validation failure message template definitions * * @var array */ protected $_messageTemplates = [ ValidatorInterface::ERROR_TITLE_IS_EMPTY => 'Name is empty',];
 
     protected $_permanentAttributes = [self::ID];
     protected $needColumnCheck = true;
     protected $groupFactory;
     protected $validColumnNames = [self::ID, self::NAME, self::DESC,];
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
          return 'city_import';
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
                    $listTitle[] = $rowTtile;
                    $entityList[$rowTtile][] = [
                    self::ID => $rowData[self::ID],
                    self::NAME => $rowData[self::NAME],
                    self::DESC => $rowData[self::DESC],];
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
                    self::NAME,
                    self::DESC]);
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