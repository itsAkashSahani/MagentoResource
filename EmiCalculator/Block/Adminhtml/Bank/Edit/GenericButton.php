<?php
namespace Ambab\EmiCalculator\Block\Adminhtml\Bank\Edit;

use Magento\Backend\Block\Widget\Context;
use Ambab\EmiCalculator\Api\BankRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;
   
    protected $bankRepository;
    
    public function __construct(
        Context $context,
        BankRepositoryInterface $bankRepository
    ) {
        $this->context = $context;
        $this->bankRepository = $bankRepository;
    }

    public function getBankId()
    {
        try {
            return $this->bankRepository->getById(
                $this->context->getRequest()->getParam('bank_id')
            )->getBankId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
?>
