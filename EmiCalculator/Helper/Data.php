<?php
namespace Ambab\EmiCalculator\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper 
{
    public function getModuleConfig()
	{
		return $this->scopeConfig->getValue('emi_control/module_control/enable', ScopeInterface::SCOPE_STORE);
	}

}
?>