<?php

namespace Albatool\HairQuizConsultant\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Mirasvit\Report\Model\Mail\Template\TransportBuilder;

class Data extends AbstractHelper
{
    const XML_PATH_TRANS_IDENTITY_EMAIL = 'trans_email/ident_general/email';

    const XML_PATH_TRANS_IDENTITY_NAME = 'trans_email/ident_general/name';

    const XML_PATH_RESPONSE_TEMPLATE = 'gensection/general/quizrestemplate';
    const XML_PATH_ENABLE = 'gensection/general/enable';

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

    public function sendQuizResponse($data)
    {
        try {
            $templateId = $this->scopeConfig->getValue(
                self::XML_PATH_RESPONSE_TEMPLATE,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $data['storeId']
            );

            $this->inlineTranslation->suspend();

            $templateParams = [
                'name' => $data['name'],
                'description' => $data['description'],
                'resultUrl' => $data['resultUrl'],
                'banner_image' => $data['banner_image'],
                'mobile_image' => $data['mobile_image'],
                'beauty_advice' => $data['beauty_advice']
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
                        'store' => $data['storeId'],
                    ]
                )
                ->setTemplateVars($templateParams)
                ->setFrom($sender)
                ->addTo($data['email'])
                ->getTransport();

            $transport->sendMessage();

            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    public function isEnabled($storeId) {
        return $templateId = $this->scopeConfig->getValue(
            self::XML_PATH_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
