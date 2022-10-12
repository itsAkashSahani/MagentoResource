<?php

namespace Albatool\SmsTrigger\Model\Order\Email;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\Template\TransportBuilderByStore;
use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;

class SenderBuilder extends \Magento\Sales\Model\Order\Email\SenderBuilder
{

    const TYPE_OCTETSTREAM       = 'application/octet-stream';
    const DISPOSITION_ATTACHMENT = 'attachment';
    const ENCODING_BASE64        = 'base64';

    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        TransportBuilder $transportBuilder,
        TransportBuilderByStore $transportBuilderByStore = null
    ) {
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $transportBuilder
        );
    }

    /**
     * Prepare and send email message
     *
     * @return void
     */
    public function send()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $this->configureEmailTemplate();

        $this->transportBuilder->addTo(
            $this->identityContainer->getCustomerEmail(),
            $this->identityContainer->getCustomerName()
        );

        $copyTo = $this->identityContainer->getEmailCopyTo();

        if (!empty($copyTo) && $this->identityContainer->getCopyMethod() == 'bcc') {
            foreach ($copyTo as $email) {
                $this->transportBuilder->addBcc($email);
            }
        }

        $templateVars = $this->templateContainer->getTemplateVars();

        if (!empty($templateVars['invoice_id'])) {
            $invoiceId = $templateVars['invoice_id'];
            if ($invoiceId) {
                $invoice = $objectManager->create('Magento\Sales\Api\InvoiceRepositoryInterface')->get($invoiceId);
                if($invoice) {
                    $pdf = $objectManager->create('Magento\Sales\Model\Order\Pdf\Invoice')->getPdf([$invoice]);
    
                    if($pdf->render()) {
                        $this->transportBuilder->addAttachment(
                            $pdf->render(),
                            $mimeType = self::TYPE_OCTETSTREAM,
                            $disposition = self::DISPOSITION_ATTACHMENT,
                            $encoding = self::ENCODING_BASE64,
                            "Invoice-{$invoiceId}.pdf"
                        );
                    }
                }
            }
        }

        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
    }
}