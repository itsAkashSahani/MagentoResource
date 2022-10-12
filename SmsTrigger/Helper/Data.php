<?php

namespace Albatool\SmsTrigger\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Mirasvit\Report\Model\Mail\Template\TransportBuilder;

/**
 * Class Data
 * @package RDewan\OrderExport\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @var string "email" path
     */
    const XML_PATH_TRANS_IDENTITY_EMAIL = 'trans_email/ident_general/email';
    /**
     * @var string "name" path
     */
    const XML_PATH_TRANS_IDENTITY_NAME = 'trans_email/ident_general/name';

    const XML_PATH_INVOICE_PDF = 'invoicepdfqrgen/general/send_invoice_pdf';

    const XML_PATH_PICKUP_ORDER_CANCEL = 'sales_email/order_cancel/pickup_template';
    const XML_PATH_ORDER_CANCEL = 'sales_email/order_cancel/template';

    const TYPE_OCTETSTREAM       = 'application/octet-stream';
    const DISPOSITION_ATTACHMENT = 'attachment';
    const ENCODING_BASE64        = 'base64';

    protected $scopeConfig;
	protected $inlineTranslation;
    protected $escaper;
    protected $transportBuilder;
    protected $logger;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        Escaper $escaper,
        TransportBuilder $transportBuilder
	) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
    }

    public function sendEmailAfterOrderChange($data, $templateId, $storeId)
    {
        try {
            $this->inlineTranslation->suspend();

            $templateParams = [
                'status' => $data['orderStatus'],
                'name' => $data['customerName'],
                'order_id' => $data['orderId']
            ];

            $senderName = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_NAME);
            $senderEmail = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_EMAIL);

            $sender = [
                'name' => $senderName,
                'email' => $senderEmail
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $storeId,
                    ]
                )
                ->setTemplateVars($templateParams)
                ->setFrom($sender)
                ->addTo($data['customerEmail'])
                ->getTransport();

            $transport->sendMessage();

            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    public function sendInvoicePdf($data, $invoiceId, $storeId)
    {
        try {

            $email = $data['customerEmail'];
            $name = $data['customerName'];
            $order_id = $data['orderId'];

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $this->inlineTranslation->suspend();

            $senderName = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_NAME);
            $senderEmail = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_EMAIL);

            $templateId = $this->scopeConfig->getValue(
                self::XML_PATH_INVOICE_PDF,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );

            $sender = [
                'name' => $senderName,
                'email' => $senderEmail
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateVars([
                    'email' => $email,
                    'name' => $name,
                    'orderId' => $order_id
                ])
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $storeId,
                    ]
                )
                ->setFrom($sender)
                ->addTo($email);

            if ($invoiceId) {
                $invoice = $objectManager->create('Magento\Sales\Api\InvoiceRepositoryInterface')->get($invoiceId);
                if($invoice) {
                    $pdf = $objectManager->create('Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$invoice]);

                    if($pdf->render()) {
                        $transport->addAttachment(
                            $pdf->render(),
                            $mimeType = self::TYPE_OCTETSTREAM,
                            $disposition = self::DISPOSITION_ATTACHMENT,
                            $encoding = self::ENCODING_BASE64,
                            "Invoice-{$invoiceId}.pdf"
                        );
                    }
                }
            }

            $transport->getTransport()->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    public function sendOrderCancelMail($data, $storeId) {
        try {
            $email = $data['customerEmail'];
            $name = $data['customerName'];
            $order_id = $data['orderId'];

            $this->inlineTranslation->suspend();

            $senderName = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_NAME);
            $senderEmail = $this->scopeConfig->getValue(self::XML_PATH_TRANS_IDENTITY_EMAIL);

            $templateVars = [
                'email' => $email,
                'name' => $name,
                'orderId' => $order_id
            ];

            if(array_key_exists('pickup', $data)) {
                $templateId = $this->scopeConfig->getValue(
                    self::XML_PATH_PICKUP_ORDER_CANCEL,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                );

                $templateVars['storeName'] = $data['storeName'];
            } 
            else {
                $templateId = $this->scopeConfig->getValue(
                    self::XML_PATH_ORDER_CANCEL,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                );
            }

            $sender = [
                'name' => $senderName,
                'email' => $senderEmail
            ];

            $transport = $this->transportBuilder
                ->setTemplateIdentifier($templateId)
                ->setTemplateVars($templateVars)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $storeId,
                    ]
                )
                ->setFrom($sender)
                ->addTo($email);

            $transport->getTransport()->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
