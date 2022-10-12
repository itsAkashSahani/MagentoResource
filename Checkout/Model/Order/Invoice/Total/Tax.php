<?php
namespace Albatool\Checkout\Model\Order\Invoice\Total;

class Tax extends \Magento\Sales\Model\Order\Invoice\Total\Tax

{
   
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $totalTax = 0;
        $baseTotalTax = 0;
        $totalDiscountTaxCompensation = 0;
        $baseTotalDiscountTaxCompensation = 0;

        $order = $invoice->getOrder();

        /** @var $item \Magento\Sales\Model\Order\Invoice\Item */
        foreach ($invoice->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();
            $orderItemQty = $orderItem->getQtyOrdered();

            if (($orderItem->getTaxAmount() || $orderItem->getDiscountTaxCompensationAmount()) && $orderItemQty) {
                if ($item->getOrderItem()->isDummy() || $item->getQty() <= 0) {
                    continue;
                }

                /**
                 * Resolve rounding problems
                 */
                $tax = $orderItem->getTaxAmount() - $orderItem->getTaxInvoiced();
                $baseTax = $orderItem->getBaseTaxAmount() - $orderItem->getBaseTaxInvoiced();
                $discountTaxCompensation = $orderItem->getDiscountTaxCompensationAmount() -
                    $orderItem->getDiscountTaxCompensationInvoiced();
                $baseDiscountTaxCompensation = $orderItem->getBaseDiscountTaxCompensationAmount() -
                    $orderItem->getBaseDiscountTaxCompensationInvoiced();
                if (!$item->isLast()) {
                    $availableQty = $orderItemQty - $orderItem->getQtyInvoiced();
                    $tax = $invoice->roundPrice($tax / $availableQty * $item->getQty());
                    $baseTax = $invoice->roundPrice($baseTax / $availableQty * $item->getQty(), 'base');
                    $discountTaxCompensation = $invoice->roundPrice(
                        $discountTaxCompensation / $availableQty * $item->getQty()
                    );
                    $baseDiscountTaxCompensation = $invoice->roundPrice(
                        $baseDiscountTaxCompensation /
                        $availableQty * $item->getQty(),
                        'base'
                    );
                }

                $item->setTaxAmount($tax);
                $item->setBaseTaxAmount($baseTax);
                $item->setDiscountTaxCompensationAmount($discountTaxCompensation);
                $item->setBaseDiscountTaxCompensationAmount($baseDiscountTaxCompensation);

                $totalTax += $tax;
                $baseTotalTax += $baseTax;
                $totalDiscountTaxCompensation += $discountTaxCompensation;
                $baseTotalDiscountTaxCompensation += $baseDiscountTaxCompensation;
            }
        }

        $taxDiscountCompensationAmt = $totalDiscountTaxCompensation;
        $baseTaxDiscountCompensationAmt = $baseTotalDiscountTaxCompensation;
        $allowedDiscountTaxCompensation = $order->getDiscountTaxCompensationAmount() -
            $order->getDiscountTaxCompensationInvoiced();
        $allowedBaseDiscountTaxCompensation = $order->getBaseDiscountTaxCompensationAmount() -
            $order->getBaseDiscountTaxCompensationInvoiced();

        if ($this->_canIncludeShipping($invoice)) {
            $totalTax += $order->getShippingTaxAmount();
            $baseTotalTax += $order->getBaseShippingTaxAmount();
            $totalDiscountTaxCompensation += $order->getShippingDiscountTaxCompensationAmount();
            $baseTotalDiscountTaxCompensation += $order->getBaseShippingDiscountTaxCompensationAmnt();

            $allowedDiscountTaxCompensation += $order->getShippingDiscountTaxCompensationAmount() -
                $order->getShippingDiscountTaxCompensationInvoiced();
            $allowedBaseDiscountTaxCompensation += $order->getBaseShippingDiscountTaxCompensationAmnt() -
                $order->getBaseShippingDiscountTaxCompensationInvoiced();

            $invoice->setShippingTaxAmount($order->getShippingTaxAmount());
            $invoice->setBaseShippingTaxAmount($order->getBaseShippingTaxAmount());
            $invoice->setShippingDiscountTaxCompensationAmount($order->getShippingDiscountTaxCompensationAmount());
            $invoice->setBaseShippingDiscountTaxCompensationAmnt($order->getBaseShippingDiscountTaxCompensationAmnt());
        }
        $allowedTax = $order->getTaxAmount() - $order->getTaxInvoiced();
        $allowedBaseTax = $order->getBaseTaxAmount() - $order->getBaseTaxInvoiced();

        /*if ($invoice->isLast()) {
            $totalTax = $allowedTax;
            $baseTotalTax = $allowedBaseTax;
            $totalDiscountTaxCompensation = $allowedDiscountTaxCompensation;
            $baseTotalDiscountTaxCompensation = $allowedBaseDiscountTaxCompensation;
        } else {*/
            $totalTax = min($allowedTax, $totalTax);
            $baseTotalTax = min($allowedBaseTax, $baseTotalTax);
            $totalDiscountTaxCompensation = min($allowedDiscountTaxCompensation, $totalDiscountTaxCompensation);
            $baseTotalDiscountTaxCompensation = min(
                $allowedBaseDiscountTaxCompensation,
                $baseTotalDiscountTaxCompensation
            );
        //}

        $invoice->setTaxAmount($order->getTaxAmount());
        $invoice->setBaseTaxAmount($order->getTaxAmount());
        $invoice->setDiscountTaxCompensationAmount($taxDiscountCompensationAmt);
        $invoice->setBaseDiscountTaxCompensationAmount($baseTaxDiscountCompensationAmt);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $totalTax + $totalDiscountTaxCompensation);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseTotalTax + $baseTotalDiscountTaxCompensation);

        return $this;
    }

}