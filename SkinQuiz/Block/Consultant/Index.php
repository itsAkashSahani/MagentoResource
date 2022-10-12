<?php

namespace Albatool\SkinQuiz\Block\Consultant;

use Magento\Catalog\Model\ProductRepository;
use Albatool\SkinQuizConsultant\Model\Skinquizconsultant;
use Mirasvit\ProductKit\Model\ResourceModel\Kit\CollectionFactory as KitCollection;
use Mirasvit\ProductKit\Model\ResourceModel\KitItem\CollectionFactory as KitItemCollection;

class Index extends \Magento\Framework\View\Element\Template
{

    protected $productRepo;
    protected $skinquiz;
    protected $kitCollection;
    protected $kitItemCollection;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        ProductRepository $productRepo,
        Skinquizconsultant $skinquiz,
        KitCollection $kitCollection,
        KitItemCollection $kitItemCollection,
        \Magento\Cms\Model\Template\FilterProvider $contentProcessor,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->productRepo = $productRepo;
        $this->skinquiz = $skinquiz;
        $this->kitCollection = $kitCollection;
        $this->kitItemCollection = $kitItemCollection;
        $this->contentProcessor = $contentProcessor;
    }

    public function getProductCollection() {
        return $this->productRepo;
    }

    public function getSkinConsultCollection($id) {
        $collection = $this->skinquiz->getCollection();
        $collection->addFieldToFilter('id', ['eq' => $id])->getData();

        return $collection;
    }

    public function getKitCollection($id) {
        $count = count($this->getSkinConsultCollection($id));

        if($count > 0) {
            $kitCollection = $this->kitCollection->create();
            $kitCollection->addFieldToFilter('name', ['eq' => 'Skin_Kit_' . $id]);
                                
            
            if(count($kitCollection) == 0) {
                $collection = $this->kitCollection->create();
                $collection->addFieldToFilter('name', ['eq' => 'Skin_Kit_Default']);
                
                return $collection;
            }
        } else {
            $kitCollection = $this->kitCollection->create();
            $kitCollection->addFieldToFilter('name', ['eq' => 'Skin_Kit_Default']);
        }

        return $kitCollection;
    }

    public function getKitItemCollection($id) {
        $collection = $this->kitItemCollection->create();
        $collection->addFieldToFilter('kit_id', ['eq' => $id]);

        return $collection;
    }

    public function getBannerImage($content) {
        // $dir = '/Albatool/SkinQuizConsultant/image';
        // $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
        //     \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        // );
        // $imageUrl = $mediaDirectory . $dir . $fileName;

        // return $imageUrl;
        return $this->contentProcessor->getPageFilter()->filter($content);
    }

    public function getMobileBannerImage($content) {
        // $dir = '/Albatool/SkinQuizConsultant/image';
        // $mediaDirectory = $this->_storeManager->getStore()->getBaseUrl(
        //     \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        // );
        // $imageUrl = $mediaDirectory . $dir . $fileName;

        // return $imageUrl;
        return $this->contentProcessor->getPageFilter()->filter($content);
    }

    public function getBeautySteps($content) {
        return $this->contentProcessor->getPageFilter()->filter($content);
    }
    
}
