<?php
namespace Albatool\CronOrder\Observer;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class ObserverforAddCustomVariable implements ObserverInterface
{

    protected $customerRepository;
    protected $date;

    public function __construct(CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    ) 
    {
        $this->customerRepository = $customerRepository;
        $this->date = $date;
    }

    
    public function execute(Observer $observer)
    {
        /** @var \Magento\Framework\App\Action\Action $controller */
        $transport = $observer->getEvent()->getTransport();
        
        if($transport->getOrder() != null)
        {
            $order = $transport->getOrder();
            $payment = $order->getPayment();
            $method = $payment->getMethodInstance();
            $methodTitle = $method->getTitle();
            $methodCode = $method->getCode();
            if($order->getShippingMethod() == 'instore_pickup'){
                if($methodCode == 'cashondelivery'){
                    $payment_title_new = "Pay at store";
                }
                else{
                    $payment_title_new = "Prepaid";
                }
            }
            else {
                if($methodCode == 'cashondelivery'){
                    $payment_title_new = "Cash on delivery";
                }
                else{
                    $payment_title_new = "Prepaid";
                }
            }
            $currentDate = $this->date->gmtDate();
            $FirstDate = $this->date->gmtDate('d-M', mktime(0, 0, 0, date('m') + 1, 1, date('Y')));
            $LastDate = $this->date->gmtDate('d', strtotime('last day of next month'));
            $LastMonth = $this->date->gmtDate('F-Y', strtotime('last day of next month'));
            $nextMonth = $this->date->gmtDate('M',strtotime('+1 month',strtotime($currentDate)));
            $coupon_code_val = strtoupper($nextMonth);
            $coupon_code_val = $coupon_code_val."20";
            $transport['order_coupon_var']   = $coupon_code_val;
            $transport['coupon_FirstDate']   = $FirstDate;
            $transport['coupon_LastDate']   = $LastDate; 
            $transport['coupon_LastMonth']   = $LastMonth;
            $transport['payment_html_new'] = $payment_title_new;
        }
    }
}