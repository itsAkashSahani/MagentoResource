<?php
namespace Albatool\CityDropList\Model\Import\CityImport;

interface RowValidatorInterface extends \Magento\Framework\Validator\ValidatorInterface
{
       const ERROR_INVALID_TITLE= 'InvalidValueTITLE';
       const ERROR_MESSAGE_IS_EMPTY = 'EmptyMessage';
       const ERROR_TITLE_IS_EMPTY = "Name is empty";
       public function init($context);
}