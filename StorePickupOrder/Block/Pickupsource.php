<?php 
namespace Albatool\StorePickupOrder\Block;


use Magento\Framework\View\Element\Template\Context;
use Albatool\StorePickupOrder\Model\Data;
use Magento\Framework\View\Element\Template;


class Pickupsource extends Template
{

    public function __construct(Context $context, Data $model)
    {
        $this->model = $model;
        parent::__construct($context);

    }


    public function getDatas()
    {
        $Datas = $this->model->getCollection();
        return $Datas;
    }
}