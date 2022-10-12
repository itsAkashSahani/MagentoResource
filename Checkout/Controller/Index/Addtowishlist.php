<?php

namespace Albatool\Checkout\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Addtowishlist extends Action {

    protected $customerSession;
    protected $wishlistRepository;
    protected $productRepository;
    protected $_messageManager;
    protected $wishlist;
    protected $_urlInterface;

    public function __construct(
    Context $context,
    \Magento\Customer\Model\Session $customerSession,
    \Magento\Wishlist\Model\WishlistFactory $wishlistRepository,
    \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
    ResultFactory $resultFactory,
    \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    \Magento\Wishlist\Model\Wishlist $wishlist,
    \Magento\Framework\UrlInterface $urlInterface
        ) {
        $this->customerSession = $customerSession;
        $this->wishlistRepository= $wishlistRepository;
        $this->productRepository = $productRepository;
        $this->resultFactory = $resultFactory;
        $this->jsonFactory = $jsonFactory;
        $this->_messageManager = $messageManager;
        $this->wishlist = $wishlist;
        $this->_urlInterface = $urlInterface;
        parent::__construct($context);
    }

    public function execute() {
        $customerId = $this->customerSession->getCustomer()->getId();
        if(!$customerId) {
           $url = $this->_urlInterface->getUrl('customer/account/login', ['_secure' => true]);
           $txt_var = __('In order to add a product to your wishlist, you need to log into your personal space or create an Yves Rocher account.');
           $txt_var = '<div class="not-login-wish"><span class="inner-not-login-wish">'.$txt_var.'</span><span class="inner-not-login-butt"><a href="'.$url.'">Continue</a></span></div>';
           $message = $txt_var;
           $jsonData = ['result' => ['status' => 200, 'redirect' => 1,'message' => $message]]; 
            $result = $this->jsonFactory->create()->setData($jsonData);
            //$this->_messageManager->addError(__('Customer not logged in.'));
            return $result;
        }
        $productId = $this->getRequest()->getParam('productId');
        $add_remove_id = $this->getRequest()->getParam('add_remove_id');

        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }

        if($add_remove_id){
            $wishlist = $this->wishlistRepository->create()->loadByCustomerId($customerId, true);

            $wishlist->addNewItem($product);
            $wishlist->save();
            $jsonData = ['result' => ['status' => 200, 'redirect' => 0, 'message' => __('Product is added into <a href="%1">Wishlist</a>.', $this->_urlInterface->getUrl('wishlist')), 'item_status' => 1]];
        }
        else{
            $wish = $this->wishlist->loadByCustomerId($customerId);
            $items = $wish->getItemCollection();
            foreach ($items as $item) {
                if ($item->getProductId() == $productId) {
                    $item->delete();
                    $wish->save();
                }
            }
            $jsonData = ['result' => ['status' => 200, 'redirect' => 0, 'message' => __('Product has been removed from your Wish List.', $product->getName()), 'item_status' => 0]];
        }

        
        $result = $this->jsonFactory->create()->setData($jsonData);
        //$this->_messageManager->addSuccess(__('Added to wishlist.'));
        return $result;
    }
} 