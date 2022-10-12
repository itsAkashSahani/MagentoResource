<?php 
namespace Albatool\Checkout\Controller\Index;

use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Controller\ResultFactory;
use Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku;

class EditQty extends \Magento\Framework\App\Action\Action
{
    protected $quoteRepository;
    private $checkoutSession;
    private $getSalableQuantityDataBySku;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        CheckoutSession $checkoutSession,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        GetSalableQuantityDataBySku $getSalableQuantityDataBySku
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
        $this->getSalableQuantityDataBySku = $getSalableQuantityDataBySku;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cart = $objectManager->get('\Magento\Checkout\Model\Cart'); 
        $cartId=$cart->getQuote()->getId();

        $itemId = $this->getRequest()->get('itemId');
        $itemQty = $this->getRequest()->get('itemQty');
        $sel_pickup_name = $this->getRequest()->get('sel_pickup_name');
        //echo "ITEM IDDD:::".$itemId."::".$itemQty;exit;

        $quote = $this->checkoutSession->getQuote();
        $quoteItems = $quote->getAllVisibleItems();
        

        //echo "EEEEE IDDD:::".$itemId."::".$itemQty;exit;
        if(!empty($quoteItems)){
            foreach($quoteItems as $item) {
                $productSku = $item->getSku();
                if($item->getId() == $itemId){
                    $cur_prod_qty = $this->getSalableQuantityDataBySku->execute($productSku);
                    //echo "PPRO::<pre>";print_r($cur_prod_qty);exit;
                    if($sel_pickup_name){
                        foreach($cur_prod_qty as $cur_qty){
                            if($sel_pickup_name == $cur_qty['stock_name']){
                                $cur_source_prod_qty = $cur_qty['qty']; 
                            }
                            else{
                                $cur_source_prod_qty = 100; 
                            }
                        }
                    }
                    else{
                        $cur_source_prod_qty = $cur_prod_qty[0]['qty']; 
                    }
                    //echo "cur prod salable qty::".$cur_source_prod_qty;exit;
                    if($itemQty <= $cur_source_prod_qty){
                        $item->setQty($itemQty);
                        $item->save();
                    }
                    else{
                        $data = "false";
                        $msg = "requested quantity not available";
                        $resp = ['data'=>$data, 'message'=>$msg];
                        $resultJson->setData($resp);
                        return $resultJson;
                    }
                }
            }
            $this->quoteRepository->save($quote);
            $quote->collectTotals();
            $data = "true";
            $resultJson->setData($data);
            return $resultJson;
       }
       else{
            $data = "false";
            $resultJson->setData($data);
            return $resultJson;
       }
    }

}