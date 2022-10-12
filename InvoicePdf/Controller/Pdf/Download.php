<?php
namespace Albatool\InvoicePdf\Controller\Pdf;

use Magento\Framework\App\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

class Download extends Action\Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    protected $messageManager;

    /**
     * @param Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     */
    public function __construct(
        Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->customerSession = $customerSession;
        $this->fileFactory = $fileFactory;
        $this->_messageManager = $messageManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $invoiceId = (int)$this->getRequest()->getParam('invoice_id');
        if(!$invoiceId){
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $this->_messageManager->addErrorMessage(__("Something went wrong"));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
        $invoice = $this->_objectManager->create('Magento\Sales\Api\InvoiceRepositoryInterface')->get($invoiceId);
        if(!$invoice || !$invoice->getId()){
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $this->_messageManager->addErrorMessage(__("Something went wrong"));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $order = $invoice->getOrder();
        $customerId = $this->customerSession->getCustomerId();

        if($order->getId()){
            $pdf = $this->_objectManager->create('Albatool\InvoicePdf\Model\Order\Pdf\Invoice')->getPdf([$invoice]);
            $date = $this->_objectManager->get('Magento\Framework\Stdlib\DateTime\DateTime')->date('Y-m-d_H-i-s');
            return $this->fileFactory->create(
                "Invoice_{$order->getId()}_{$date}.pdf",
                $pdf->render(),
                DirectoryList::VAR_DIR,
                'application/pdf'
            );

        }

        $this->_messageManager->addErrorMessage(__("Something went wrong"));
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->_redirect->getRefererUrl());
        return $resultRedirect;
    }
}
