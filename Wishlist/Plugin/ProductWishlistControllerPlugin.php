<?php

namespace Albatool\Wishlist\Plugin;

use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Wishlist\Controller\AbstractIndex;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Json\Helper\Data as JsonHelper;

class ProductWishlistControllerPlugin
{

    private $resultJson;
    protected $jsonHelper;
    protected $customerSession;
    protected $messageManager;

    /**
     * ProductWishlistControllerPlugin constructor.
     *
     * @param Json $resultJson
     */
    public function __construct(
        Json $resultJson,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        JsonHelper $jsonHelper,
        CustomerSession $customerSession
    ) {
        $this->resultJson = $resultJson;
        $this->jsonHelper = $jsonHelper;
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
    }

    
    // public function beforeDispatch(AbstractIndex $subject, RequestInterface $request)
    // {
    //     if ($request->isAjax() && !$this->customerSession->isLoggedIn()) {
    //         $response = $subject->getResponse();
    //         if ($response->isRedirect()) {
    //             $response
    //                 ->clearHeader('Location')
    //                 ->setStatusCode(200);
    //         }

    //         $response->representJson(
    //             $this->jsonHelper->jsonEncode(
    //                 [
    //                     'success' => false,
    //                     'error'   => 'not_logged_in'
    //                 ]
    //             )
    //         );
    //     }
    // }

    /**
     * @param AbstractIndex $subject
     * @param ResultInterface $result
     * @return $this|ResultInterface
     */
    public function afterExecute(AbstractIndex $subject, ResultInterface $result)
    {
        if ($subject->getRequest()->getParam('isAjax', false)) {
            // $this->messageManager->getMessages(true);

            return $this->resultJson->setData([
                'success' => true,
                'messages' => __('Success')
            ]);
        }

        return $result;
    }
}