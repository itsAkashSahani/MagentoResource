<?php

namespace Albatool\Wishlist\Plugin\Wishlist\Helper;

use Magento\Framework\App\Http\Context;
use Magento\Wishlist\Helper\Data as HelperWishlist;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Session as CustomerSession;

class DataPlugin
{
    /**
     * @var PostHelper
     */
    protected $postHelper;

    /**
     * @var Http|RequestInterface
     */
    protected $request;

    /**
     * @var Json
     */
    private $serializer;

    /**
     * @var Context
     */
    private $httpContext;

    /**
     * @var boolean
     */
    private $isWishlistPage;

    private $customerSession;

    /**
     * DataPlugin constructor.
     *
     * @param PostHelper $postHelper
     * @param Context $httpContext
     * @param Json $serializer
     * @param RequestInterface $request
     */
    public function __construct(
        PostHelper $postHelper,
        Context $httpContext,
        Json $serializer,
        RequestInterface $request,
        CustomerSession $customerSession
    ) {
        $this->postHelper = $postHelper;
        $this->request = $request;
        $this->serializer = $serializer;
        $this->httpContext = $httpContext;
        $this->customerSession = $customerSession;
    }

    /**
     * Check Is Logged In
     *
     * @return bool
     */
    protected function isLoggedIn()
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    public function afterGetAddParams(HelperWishlist $subject, $result, $item, $params = [])
    {
        if ($this->isLoggedIn() && is_string($result)) {
            $data = $this->serializer->unserialize($result);
            if (!empty($data['action']) && !empty($data['data'])) {
                $data['data']['isAjax'] = true;
                $data['data']['showLoader'] = true;
                return $this->serializer->serialize($data);
            }
        }

        return $result;
    }
}