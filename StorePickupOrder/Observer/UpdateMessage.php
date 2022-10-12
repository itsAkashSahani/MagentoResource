<?php
namespace Albatool\StorePickupOrder\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

class UpdateMessage implements ObserverInterface
{
    /** @var \Magento\Framework\Message\ManagerInterface */
    protected $messageManager;

    /** @var \Magento\Framework\UrlInterface */
    protected $url;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $managerInterface,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->messageManager = $managerInterface;
        $this->url = $url;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
    	//$this->messageManager->addSuccessMessage($message);
        $messageCollection = $this->messageManager->getMessages(true);
        $cartLink = '<a href="'. $this->url->getUrl('checkout/cart') .'">View Caskfjrt/Checkout</a>';
        //$this->messageManager->addSuccess($messageCollection->getLastAddedMessage()->getText() . '  ' . $cartLink);
    }
}