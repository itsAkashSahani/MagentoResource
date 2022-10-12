<?php
namespace Albatool\Checkout\Model\Sales\Total\Quote;

class Tax extends \Magento\Tax\Model\Sales\Total\Quote\Tax

{
   
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

       /* your calculation here goes here */
        $grandTotalVal = $quote->getGrandTotal();
        $taxPercentage = 15; 
        $totalTax = ($grandTotalVal/(100+$taxPercentage)*$taxPercentage);
        $total->setTaxAmount($totalTax);
        $total->setBaseTaxAmount($totalTax);
        return $this;
    }

}