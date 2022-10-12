<?php
namespace Albatool\CategoryAttribute\Model\Category;
 
class DataProvider extends \Magento\Catalog\Model\Category\DataProvider
{
 
	protected function getFieldsMap()
	{
    	$fields = parent::getFieldsMap();
        $fields['content'][] = 'category_first_banner'; // custom image field
        $fields['content'][] = 'category_second_banner'; // custom image field
        $fields['content'][] = 'category_third_banner'; // custom image field
    	
    	return $fields;
	}
}