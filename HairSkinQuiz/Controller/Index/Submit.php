<?php
namespace Albatool\HairSkinQuiz\Controller\Index;

use Magento\Framework\App\Action\Context;
use Albatool\HairSkinQuiz\Model\HairskinquizFactory;
use Magento\Framework\Controller\ResultFactory; 
use Magento\Framework\UrlInterface;
use Albatool\HairQuizConsultant\Model\HairquizconsultantFactory;
use Albatool\HairQuizConsultant\Helper\Data;
use Albatool\HairQuizConsultant\Model\Hairquizconsultant\Image;

class Submit extends \Magento\Framework\App\Action\Action
{
	protected $modelHairFactory;
	protected $resultRedirectFactory;
	protected $urlBuilder;
	protected $_messageManager;
	protected $productCollectionFactory;
	protected $modelHairConsultFactory;
	protected $storeManager;
	protected $helper;
	protected $image;
	protected $contentProcessor;

	public function __construct(
		Context $context,
		HairskinquizFactory $modelHairFactory,
		\Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
		UrlInterface $urlBuilder,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Catalog\Model\ProductRepository $productCollectionFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		HairquizconsultantFactory $modelHairConsultFactory,
		Data $helper,
		Image $image,
		\Magento\Cms\Model\Template\FilterProvider $contentProcessor
	){
		$this->modelHairFactory = $modelHairFactory;
		$this->resultRedirectFactory = $resultRedirectFactory;
		$this->urlBuilder = $urlBuilder;
		$this->_messageManager = $messageManager;
		$this->productCollectionFactory = $productCollectionFactory;
		$this->modelHairConsultFactory = $modelHairConsultFactory;
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
        	$dob_val = $params['day']."-".$params['month']."-".$params['year'];
        	$question1 = $params['hair_length_val']."-".$params['hair_thickness_val'];
        	$hairModel = $this->modelHairFactory->create();
			$hairModel->setHairSkinQuizName($params['name']);
			$hairModel->setHairSkinQuizEmail($params['Email']);
			$hairModel->setHairSkinQuizGender($params['gender']);
			$hairModel->setHairSkinQuizDob($dob_val);

			if(isset($params['medical'])) {
				$hairModel->setHairSkinQuizMedicalCond($params['medical']);
			}

			$hairModel->setHairSkinQuizQuetion1($question1);
			$hairModel->setHairSkinQuizQuetion2($params['hair_scale_val']);
			$hairModel->setHairSkinQuizQuetion3($params['hair_describelength_val']);
			$hairModel->setHairSkinQuizQuetion4($params['hair_beauty_val']);
			$hairModel->save();
			$resultId = $this->getResultId($params);
			$vars = $this->getResponseVars($resultId, $params);
			$this->helper->sendQuizResponse($vars);
			$argument = array('resid' => $resultId, 'req_name' => $params['name']);
			$redirecturl = $this->urlBuilder->getUrl('hairquiz/index/consultant', ['_current' => true,'_use_rewrite' => true, '_query' => $argument]);
			return $this->resultRedirectFactory->create()->setUrl($redirecturl);
        }
        else{
        	$this->_messageManager->addErrorMessage('Some error has been occurred. Please try after some time.');
        	$resultRedirect->setUrl($this->_redirect->getRefererUrl());

        	return $resultRedirect;
        }
    }

	public function getResultId($params){
		$storeCode = $this->storeManager->getStore()->getCode();

    	$params_arr = [
			$params['hair_scale_val'], 
			$params['hair_describelength_val'], 
			$params['hair_beauty_val']
		];

		$resultId = 0;

    	$params_str = implode('::', $params_arr);
    	$collection = $this->modelHairConsultFactory->create()->getCollection()
						->addFieldToFilter('store_code', ['neq' => 'default'])
						->addFieldToFilter('store_code', ['in' => [$storeCode, 'both']]);
    	foreach($collection as $coll){
    		if(strcmp($coll['hair_quiz_combination'], $params_str) === 0)
    		{
    			$resultId = $coll->getId();
    		}
    	}

		if($resultId == 0) {
			$defaultCollection = $this->modelHairConsultFactory->create()->getCollection()->addFieldToFilter('store_code', ['eq' => 'default']);
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
		$collection = $this->modelHairConsultFactory->create()->getCollection()
						->addFieldToFilter('id', $id);

		$vars = [];
		$vars['storeId'] = $this->storeManager->getStore()->getId();
		$vars['name'] = $params['name'];
		$vars['email'] = $params['Email'];

		foreach($collection as $coll){
			$vars['id'] = $coll['id'];
			$vars['resultUrl'] = $this->storeManager->getStore()->getUrl('hairquiz/index/consultant') . '?req_name='.urlencode($params['name']).'&resid='.$coll['id'];
			$vars['description'] = $coll['hair_quiz_result_description'];
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