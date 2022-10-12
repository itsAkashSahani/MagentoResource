<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Albatool\StorePickupOrder\Controller\Adminhtml\Order;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;

class Index extends \Magento\Sales\Controller\Adminhtml\Order\Index
{
    public function execute()
    {
    	//echo "dfgh";die;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $extensionUser = $objectManager->get('Magento\Backend\Model\Auth\Session')->getUser();
        $role_id=$extensionUser->getRole()->getData('role_id');
        $role_name=$extensionUser->getRole()->getData('role_name');
	    if(($role_name == 'Administrators') || ($role_id == '1'))
	    {
	            $resultPage = $this->_initAction();
	            $resultPage->getConfig()->getTitle()->prepend(__('Orders'));
	            return $resultPage;
	    }else{
	        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
	        $admin_Name = $objectManager->create('Magento\Backend\Helper\Data')->getAreaFrontName();
	        $url = $this->getUrl().$admin_Name."/storepickuporder/standard/index/";
	            $redirect->setUrl($url);
	        return $redirect;
        }  
    }
}
