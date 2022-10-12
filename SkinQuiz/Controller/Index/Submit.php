<?php
namespace Albatool\SkinQuiz\Controller\Index;

use Magento\Framework\App\Action\Context;
use Albatool\SkinQuiz\Model\SkinquizFactory;
use Magento\Framework\Controller\ResultFactory; 
use Magento\Framework\UrlInterface;
use Albatool\SkinQuizConsultant\Model\SkinquizconsultantFactory;
use Albatool\HairQuizConsultant\Helper\Data;
use Albatool\SkinQuizConsultant\Model\Skinquizconsultant\Image;


class Submit extends \Magento\Framework\App\Action\Action
{
	protected $modelSkinFactory;
	protected $resultRedirectFactory;
	protected $urlBuilder;
	protected $_messageManager;
	protected $modelSkinConsultFactory;
	protected $storeManager;
	protected $helper;
	protected $image;
	protected $contentProcessor;

	public function __construct(
		Context $context,
		SkinquizFactory $modelSkinFactory,
		\Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
		UrlInterface $urlBuilder,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		SkinquizconsultantFactory $modelSkinConsultFactory,
		Data $helper,
		Image $image,
		\Magento\Cms\Model\Template\FilterProvider $contentProcessor
	){
		$this->modelSkinFactory = $modelSkinFactory;
		$this->resultRedirectFactory = $resultRedirectFactory;
		$this->urlBuilder = $urlBuilder;
		$this->_messageManager = $messageManager;
		$this->modelSkinConsultFactory = $modelSkinConsultFactory;
		$this->storeManager = $storeManager;
		$this->helper = $helper;
		$this->image = $image;
		$this->contentProcessor = $contentProcessor;
        parent::__construct($context);
    }
	public function execute()
    {
    	$resultRedirect = $this->resultRedirectFactory->create();
    	$params = $this->getRequest()->getParams();

        if(!empty($params)){
        	$hairModel = $this->modelSkinFactory->create();
			$hairModel->setSkinQuizName($params['name']);
			$hairModel->setSkinQuizEmail($params['Email']);
			$hairModel->setSkinQuizGender($params['gender']);
			$hairModel->setSkinQuizDob("");

			if(isset($params['medical'])) {
				$hairModel->setSkinQuizMedicalCond($params['medical']);
			}
			
			$hairModel->setSkinQuizQuetion1($params['skin_type_val']);
			$hairModel->setSkinQuizQuetion2($params['skin_concern_val']);
			$hairModel->setSkinQuizQuetion3($params['skin_beauty_val']);
			$hairModel->save();
			$resultId = $this->getReultId($params);
			$vars = $this->getResponseVars($resultId, $params);
			$this->helper->sendQuizResponse($vars);
			$argument = array('req_name' => $params['name'], 'resid' => $resultId);
			$redirecturl = $this->urlBuilder->getUrl('skinquiz/index/consultant', ['_current' => true,'_use_rewrite' => true, '_query' => $argument]);
			return $this->resultRedirectFactory->create()->setUrl($redirecturl);
        }
        else{
        	$this->_messageManager->addErrorMessage('Some error has been occurred. Please try after some time.');
        	$resultRedirect->setUrl($this->_redirect->getRefererUrl());
        	return $resultRedirect;
        }
    }

	public function getReultId($params){
		$storeCode = $this->storeManager->getStore()->getCode();
    	
    	$params_arr = [
			$params['skin_type_val'], 
			$params['skin_concern_val'], 
			$params['skin_beauty_val']
		];

		$resultId = 0;

    	$params_str = implode('::', $params_arr);
		$collection = $this->modelSkinConsultFactory->create()->getCollection()
						->addFieldToFilter('store_code', ['neq' => 'default'])
						->addFieldToFilter('store_code', ['in' => [$storeCode, 'both']]);
    	foreach($collection as $coll){
    		if(strcmp($coll['skin_quiz_combination'], $params_str) === 0)
    		{
    			$resultId = $coll->getId();
    		}
    	}

		if($resultId == 0) {
			$defaultCollection = $this->modelSkinConsultFactory->create()->getCollection()->addFieldToFilter('store_code', ['eq' => 'default']);
			foreach($defaultCollection as $coll){
				if($coll['store_code'] === 'default')
				{
					$resultId = $coll->getId();
				}
			}
		}

    	return $resultId;
    }

	public function getResponseVars($id, $params) {
		$collection = $this->modelSkinConsultFactory->create()->getCollection()
						->addFieldToFilter('id', $id);

		$vars = [];
		$vars['storeId'] = $this->storeManager->getStore()->getId();
		$vars['name'] = $params['name'];
		$vars['email'] = $params['Email'];

		foreach($collection as $coll){
			$vars['id'] = $coll['id'];
			$vars['resultUrl'] = $this->storeManager->getStore()->getUrl('skinquiz/index/consultant') . '?req_name='.urlencode($params['name']).'&resid='.$coll['id'];
			$vars['description'] = $coll['skin_quiz_result_description'];
			$vars['banner_image'] = $this->getBeautySteps($coll['banner_image']);
			$vars['mobile_image'] = $this->getBeautySteps($coll['mobile_banner_image']);
			$vars['beauty_advice'] = $this->getBeautySteps($coll['beauty_steps']);
		}

		return $vars;
	}

	public function getBeautySteps($content) {
        return $this->contentProcessor->getPageFilter()->filter($content);
    }
}