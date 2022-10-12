<?php
namespace Ambab\EmiCalculator\Block;
class Display extends \Magento\Framework\View\Element\Template
{
	protected $helper;
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Ambab\EmiCalculator\Helper\Data $helperData
		)
	{
		parent::__construct($context);
		$this->helper = $helperData;
	}

	public function isEnabled()
	{
		return $this->helper->getModuleConfig();
	}
}