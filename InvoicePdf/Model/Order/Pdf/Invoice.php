<?php
namespace Albatool\InvoicePdf\Model\Order\Pdf;

use PHPQRCode\QRcode;
use Magento\Sales\Model\RtlTextHandler;
use Magento\Framework\App\Filesystem\DirectoryList;

class Invoice extends \Magento\Sales\Model\Order\Pdf\Invoice
{
    protected $storeMobile;
    protected $cellWidths;

    protected $rtlTextHandler;

    public function getPdf( $invoices = [] )
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');

        $pdf = new \Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new \Zend_Pdf_Style();
        $this->_setFontBold($style, 10);

        foreach ($invoices as $invoice)
        {
            if ($invoice->getStoreId())
            {
                //$this->_localeResolver->emulate($invoice->getStoreId());
                $this->_storeManager->setCurrentStore($invoice->getStoreId());
            }
            $page = $this->newPage();

            $margin = 25;
            $down = 0 + $margin;
            $left = 0 + $margin;
            $right = $page->getWidth() - $margin;
            $up = $page->getHeight() - $margin;
            $center = $page->getWidth() / 2;

            $order = $invoice->getOrder();

            $phone = $this->_scopeConfig->getValue(
                'general/store_information/phone',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            $this->storeMobile = $phone;

            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                $this->_scopeConfig->isSetFlag(
                    self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $order->getStoreId()
                )
            );

            // $this->_drawHeader($page);

            $this->drawTableHeader($page);

            $this->drawItemsTable($invoice, $page, $order);
            $page = end($pdf->pages);

            if ($this->y <= $down + 110/*YOUR PAGE BOTTOM*/){
                $this->newPage();
                $page = end($pdf->pages);
                $this->y = $up;
            }

            // $this->insertTotals($page, $invoice);
            if ($invoice->getStoreId())
            {
                // $this->_localeResolver->revert();
            }

            $this->summaryBlock($page, $invoice, $order);

            if ($this->y <= $down + 160  /*YOUR PAGE BOTTOM*/){
                $this->newPage();
                $page = end($pdf->pages);
                $this->y = $up;
            }

            $this->footerBlock($page, $order);

        }

        $this->_afterGetPdf();
        return $pdf;
    }
  
    protected function insertOrder(&$page, $obj, $putOrderId = true)
    {
        if ($obj instanceof \Magento\Sales\Model\Order)
        {
            $shipment = null;
            $order = $obj;
        }
        elseif ($obj instanceof \Magento\Sales\Model\Order\Shipment)
        {
            $shipment = $obj;
            $order = $shipment->getOrder();
        }

        $this->y = $this->y ? $this->y : 815;
        $top = $this->y;

        $margin = 25;
        $down = 0 + $margin;
        $left = 0 + $margin;
        $right = $page->getWidth() - $margin;
        $up = $page->getHeight() - $margin;
        $center = $page->getWidth() / 2;

        $qrwidth = 75;
        $qrheight = 75;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $rtlText = $objectManager->get(RtlTextHandler::class);

        foreach ($order->getInvoiceCollection() as $invoice)
        {
            $invoice_id = $invoice->getIncrementId();
        }
        
        $dictionary = $objectManager->create('Magento\Framework\App\Language\Dictionary');

        $translate = $dictionary->getDictionary('ar_SA');

        $seller_name = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('invoicepdfqrgen/general/invoicepdfqrsellername', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $vat_reg_no = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('invoicepdfqrgen/general/invoicepdfqrvatnumber', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $invoice_total_amt = $invoice->getBaseGrandTotal();
        $invoice_tax_amt = $invoice->getTaxAmount();
        $invoice_created_date = $invoice->getCreatedAt();
        $codeContents=$seller_name." ".$vat_reg_no." ".$invoice_created_date." ".round($invoice_total_amt,2)." ".round($invoice_tax_amt,2);
        $codeContents = base64_encode($codeContents);
        $fileSystem = $objectManager->create('\Magento\Framework\Filesystem');   
        $tempDir = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('magecomp/');
        $fileName = 'ord_'.$order->getRealOrderId().md5($codeContents).'.png';

        $pngAbsoluteFilePath = $tempDir.$fileName;

        $fileDriver = $objectManager->create('\Magento\Framework\Filesystem\Driver\File');
       
        if (!$fileDriver->isExists($pngAbsoluteFilePath))
        {
            QRcode::png($codeContents, $pngAbsoluteFilePath, 'L', 4, 2);
        } 
       
        $image = \Zend_Pdf_Image::imageWithPath($pngAbsoluteFilePath);

        $y1 = $up - $qrheight;
        $y2 = $up;
        $x1 = 480;
        $x2 = $x1 + $qrwidth;

        $top = $y1;

        $page->drawImage($image, $x1, $y1, $x2, $y2);

        $this->_setFontBold($page, 12);
        $page->drawText($rtlText->reverseRtlText($translate['Al-Batool International Trading Co.Ltd.']), $center - 130, $up - 30, 'UTF-8');
        $page->drawText('Al-Batool International Trading Co.Ltd.', $center - 105, $up - 50, 'UTF-8');

        $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $top -= 5;
        $page->drawLine($left, $top, $right, $top);

        $this->_setFontBold($page, 12);
        $top -= 20;
        $page->drawText('SIMPLIFIED TAX INVOICE', $left + 100, $top, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['SIMPLIFIED TAX INVOICE']), $left + 280, $top, 'UTF-8');

        $top -= 25;

        // Invoice Box
        $page->drawRectangle($left, $top - 5 ,$left + 405, $top + 15, \Zend_Pdf_Page::SHAPE_DRAW_STROKE);

        // Payment Method Box
        $page->drawRectangle($left + 405, $top - 25 , $right, $top + 15, \Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        
        $this->_setFontRegular($page, 8);

        if ($invoice_id)
        {
            $page->drawText('E-Invoice', $left + 5, $top + 2, 'UTF-8');
            $page->drawText($invoice_id, $left + 67, $top + 2, 'UTF-8');
            $page->drawText($rtlText->reverseRtlText($translate['E-Invoice']), $left + 117, $top + 2, 'UTF-8');
            $page->drawLine($left + 175, $top - 5, $left + 175, $top + 15);
        }

        $timezoneInterface = $objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
        $formattedDate = $timezoneInterface->date($order->getCreatedAt())->format('d-F-Y/H:i:s');
        
        $page->drawText('E-Invoice Date/Time :', $left + 180, $top + 2, 'UTF-8');
        $page->drawText($formattedDate, $left + 265, $top + 2, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['E-Invoice Date/Time :']), $left + 355, $top + 2, 'UTF-8');

        $shippingMethod = $order->getShippingMethod();
        $payment = $order->getPayment();
        $method = $payment->getMethodInstance();
        $methodCode = $method->getCode();

        if($order->getShippingMethod() == 'instore_pickup') {
            if($methodCode == 'cashondelivery') {
                $paymentMethod = "Pay at Store";
            }
            else {
                $paymentMethod = "Prepaid";
            }
        }
        else {
            if($methodCode == 'cashondelivery') {
                $paymentMethod = "Cash on Delivery";
            }
            else {
                $paymentMethod = "Prepaid";
            }
        }

        $page->drawText($rtlText->reverseRtlText($translate["Payment Method : {$paymentMethod}"]), $left + 410, $top, 'UTF-8');
        $page->drawText("Payment Method : {$paymentMethod}", $left + 410, $top - 15, 'UTF-8');

        $top -= 20;
        $page->drawRectangle($left, $top - 5 , $left + 405, $top + 15, \Zend_Pdf_Page::SHAPE_DRAW_STROKE);

        if ($putOrderId)
        {
            $page->drawText('Online Order No.', $left + 5, $top + 2, 'UTF-8');
            $page->drawText($order->getRealOrderId(), $left + 67, $top + 2, 'UTF-8');
            $page->drawText($rtlText->reverseRtlText($translate['Online Order No.']), $left + 112, $top + 2, 'UTF-8');
            $page->drawLine($left + 175, $top - 5, $left + 175, $top + 15);

        }

        // Vat Number
        $vat_reg_no = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface')->getValue('invoicepdfqrgen/general/invoicepdfqrvatnumber', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $page->drawText('VAT Number', $left + 180, $top + 2, 'UTF-8');
        $page->drawText($vat_reg_no, $left + 265, $top + 2, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['VAT Number']), $left + 355, $top + 2, 'UTF-8');

        // Delivery Method
        $top -= 20;
        $deliveryMethod = $order->getShippingMethod() == 'instore_pickup' ? 'Click and Collect' : 'Home Delivery';

        $page->drawRectangle($left, $top - 5 ,$right, $top + 15, \Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $page->drawText("Delivery Method : {$deliveryMethod}", $center - 150, $top + 2, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Delivery Method : {$deliveryMethod}"]), $center + 20, $top + 2, 'UTF-8');

        $top -= 25;
        $this->_setFontBold($page, 10);
        $page->setFillColor(new \Zend_Pdf_Color_Html('#c0c4b4'));
        $page->drawRectangle($left, $top - 15 ,$center, $top + 15);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));
        $page->drawText("Shipping Address:", $left + 10, $top - 2, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Shipping Address:"]), $center - 85, $top - 2, 'UTF-8');
        
        $page->setFillColor(new \Zend_Pdf_Color_Html('#c0c4b4'));
        $page->drawRectangle($center, $top - 15 ,$right, $top + 15);
        $page->setFillColor(new \Zend_Pdf_Color_Rgb(0, 0, 0));
        $page->drawText("Customer Information:", $center + 10, $top - 2, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Customer Information:"]), $right - 100, $top - 2, 'UTF-8');

        $this->_setFontRegular($page, 10);

        $top -= 30;
        if (!$order->getIsVirtual())
        {
            $shippingMethod = $order->getShippingDescription();
            $shippingAddress = [];
            $shippingAddress[] = $order->getShippingAddress()->getFirstname() . " " . $order->getShippingAddress()->getLastname() . ",";
            $shippingAddress[] = $order->getShippingAddress()->getStreet()[0] . ",";
            $shippingAddress[] = $order->getShippingAddress()->getCity() . ", " . str_replace(" Region", "", $order->getShippingAddress()->getRegion()) . ", Saudi Arabia";
            $shippingAddress[] = "Mobile # " . $order->getShippingAddress()->getTelephone();

        }

        $billingAddress = $this->_formatAddress($this->addressRenderer->format($order->getBillingAddress(), 'pdf'));
        $addressesHeight = $this->_calcAddressHeight($shippingAddress);

        $page->drawRectangle($left, $top - $addressesHeight ,$center, $top + 15, \Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $this->y = $top;

        $count = 1;
        foreach ($shippingAddress as $value)
        {
            if ($value !== '')
            {
                $text = [];
                foreach ($this->string->split($value, 45, true, true) as $_value)
                {
                    $text[] = $_value;
                }
                foreach ($text as $part)
                {
                    $page->drawText(strip_tags(ltrim($part)), 35, $this->y, 'UTF-8');
                    $this->y -= 12;
                }
            }
        }
        
        $page->drawRectangle($center, $top - $addressesHeight ,$right, $top + 15, \Zend_Pdf_Page::SHAPE_DRAW_STROKE);
        $this->y = $top;

        $name = $order->getBillingAddress()->getFirstName() . " " . $order->getBillingAddress()->getLastName();
        $mobile = $order->getBillingAddress()->getTelephone();
        $city = $order->getBillingAddress()->getCity();

        $this->_setFontBold($page, 10);
        $page->drawText("Name: {$name}", $center + 10, $this->y, 'UTF-8');

        $this->_setFontRegular($page, 10);
        $page->drawText("Mobile: {$mobile}", $center + 10, $this->y -= 12, 'UTF-8');
        $page->drawText("Address: {$city}", $center + 10, $this->y -= 12, 'UTF-8');

        $top -= $addressesHeight;
        $this->y = $top;
    }

    protected function _calcAddressHeight($address)
    {
        $y = 0;
        foreach ($address as $value) {
            if ($value !== '') {
                $text = [];
                foreach ($this->string->split($value, 55, true, true) as $_value) {
                    $text[] = $_value;
                }
                foreach ($text as $part) {
                    $y += 12;
                }
            }
        }
        return $y;
    }

    protected function _calcNameHeight($name)
    {
        $y = 0;
        if ($name !== '') {
            $text = [];
            foreach ($this->string->split($name, 45, true, true) as $_value) {
                $text[] = $_value;
            }
            foreach ($text as $part) {
                $y += 20;
            }
        }
        return $y;
    }

    protected function _calcSkuHeight($sku)
    {
        $y = 0;
        if ($sku !== '') {
            $text = [];
            foreach ($this->string->split($sku, 7, true, true) as $_value) {
                $text[] = $_value;
            }
            foreach ($text as $part) {
                $y += 20;
            }
        }
        return $y;
    }

    public function drawTableHeader(\Zend_Pdf_Page $page) {
        /* Add table head */
        $top = $this->y;

        $margin = 25;
        $down = 0 + $margin;
        $left = 0 + $margin;
        $right = $page->getWidth() - $margin;
        $up = $page->getHeight() - $margin;
        $center = $page->getWidth() / 2;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dictionary = $objectManager->create('Magento\Framework\App\Language\Dictionary');
        $translate = $dictionary->getDictionary('ar_SA');
        $rtlText = $objectManager->get(RtlTextHandler::class);

        $this->_setFontBold($page, 7);
        $headWidth = 50;
        $middle = $top - ($headWidth / 2) - 2;
        $page->setFillColor(new \Zend_Pdf_Color_Html('#c0c4b4'));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $top, 570, $top - $headWidth);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale('0'));

        $page->drawText('#', $left + 5, $middle, 'UTF-8');
        $page->drawLine($left + 15, $top, $left + 15, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Item']), $left + 20, $middle + 18, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Code']), $left + 20, $middle + 10, 'UTF-8');
        $page->drawText('Item', $left + 20, $middle - 7, 'UTF-8');
        $page->drawText('Code', $left + 20, $middle - 15, 'UTF-8');
        $page->drawLine($left + 45, $top, $left + 45, $top - $headWidth);

        $page->drawText('Item Description', $left + 60, $middle, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Item Description']), $left + 185, $middle, 'UTF-8');
        $page->drawLine($left + 223, $top, $left + 223, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Qty']), $left + 228, $middle + 10, 'UTF-8');
        $page->drawText('Qty', $left + 228, $middle - 7, 'UTF-8');
        $page->drawLine($left + 253, $top, $left + 253, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Unit Price']), $left + 258, $middle + 18, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Excl.']), $left + 263, $middle + 11, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['VAT']), $left + 261, $middle + 5, 'UTF-8');
        $page->drawText('Unit Price', $left + 258, $middle - 5, 'UTF-8');
        $page->drawText('Excl. VAT', $left + 258, $middle - 11, 'UTF-8');
        $page->drawText('SAR', $left + 265, $middle - 18, 'UTF-8');
        $page->drawLine($left + 295, $top, $left + 295, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Gross']), $left + 310, $middle + 20, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Amount']), $left + 305, $middle + 13, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Excl. VAT']), $left + 298, $middle + 8, 'UTF-8');
        $page->drawText('Gross', $left + 310, $middle, 'UTF-8');
        $page->drawText('Amount', $left + 305, $middle - 6, 'UTF-8');
        $page->drawText('Excl. VAT', $left + 303, $middle - 13, 'UTF-8');
        $page->drawText('SAR', $left + 312, $middle - 21, 'UTF-8');
        $page->drawLine($left + 340, $top, $left + 340, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Discount']), $left + 345, $middle + 15, 'UTF-8');
        $page->drawText('Discount', $left + 348, $middle - 8, 'UTF-8');
        $page->drawText('SAR', $left + 350, $middle - 15, 'UTF-8');
        $page->drawLine($left + 380, $top, $left + 380, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Excl.']), $left + 400, $middle + 18, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['VAT']), $left + 395, $middle + 12, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Subtotal']), $left + 383, $middle + 6, 'UTF-8');
        $page->drawText('Subtotal', $left + 395, $middle - 7, 'UTF-8');
        $page->drawText('Excl. VAT SAR', $left + 385, $middle - 15, 'UTF-8');
        $page->drawLine($left + 438, $top, $left + 438, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Tax']), $left + 449, $middle + 12, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Added']), $left + 447, $middle + 6, 'UTF-8');
        $page->drawText('VAT (15%)', $left + 444, $middle - 7, 'UTF-8');
        $page->drawText('SAR', $left + 450, $middle - 15, 'UTF-8');
        $page->drawLine($left + 480, $top, $left + 480, $top - $headWidth);

        $page->drawText($rtlText->reverseRtlText($translate['Total Amount']), $left + 500, $middle + 12, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Incl. VAT']), $left + 495, $middle + 6, 'UTF-8');
        $page->drawText("Total Amount", $left + 490, $middle - 7, 'UTF-8');
        $page->drawText('Incl. VAT SAR', $left + 488, $middle - 15, 'UTF-8');

        $this->cellWidths = [$left + 15, $left + 45, $left + 223, $left + 253, $left + 295, $left + 340, $left + 380, $left + 438, $left + 480];

        $this->y = $top - $headWidth;
    }

    public function getTableData($invoice) {
        /* Add body */
        $count = 1;
        $tableData = [];
        foreach ($invoice->getAllItems() as $item)
        {
            if ($item->getOrderItem()->getParentItem())
            {
                continue;
            }

            $discountAmt = empty($item->getDiscountAmount()) ? 0 : $item->getDiscountAmount();
            $subtotal = $item->getBaseRowTotal() - $discountAmt;
            $tax = $item->getBaseTaxAmount();
            // $netAmt = (float) str_replace(",", "", $subtotal) + $tax;
            $netAmt = $item->getBaseRowTotalInclTax();

            $tableData[$count]['index'] = $count;
            $tableData[$count]['sku'] = $item->getSku();
            $tableData[$count]['name'] = $item->getName();
            $tableData[$count]['qty'] = (int)$item->getQty();
            $tableData[$count]['unit_price'] = $item->getBasePrice();
            $tableData[$count]['gross_price'] = $item->getBaseRowTotal();
            $tableData[$count]['discount'] = $discountAmt;
            $tableData[$count]['subtotal'] = $subtotal;
            $tableData[$count]['vat'] = $tax;
            $tableData[$count]['net_total'] = $netAmt;
            $count ++;
        }

        return $tableData;
    }

    public function drawItemsTable($invoice, $page, $order) {
        $top = $this->y;
        $data = $this->getTableData($invoice);

        $margin = 25;
        $down = 0 + $margin;
        $left = 0 + $margin;
        $right = $page->getWidth() - $margin;
        $up = $page->getHeight() - $margin;
        $center = $page->getWidth() / 2;
        
        foreach($data as $item) {
            $this->_setFontRegular($page, 8);
            // $nameHeight = $this->_calcNameHeight($item['name']);
            // $skuHeight = $this->_calcSkuHeight($item['sku']);

            $nameHeight = max($this->_calcSkuHeight($item['sku']), $this->_calcNameHeight($item['name']));

            if ($top <= $down + $nameHeight/*YOUR PAGE BOTTOM*/){
                $page = $this->newPage();
                $this->y = $up;
                $this->drawTableHeader($page);
                $top = $this->y;
            }
            
            $middle = $top - 15;
            $page->drawRectangle($left,  $top - $nameHeight, $right, $top, \Zend_Pdf_Page::SHAPE_DRAW_STROKE); 

            $page->drawText($item['index'], $left + 5, $middle, 'UTF-8');
            $page->drawLine($this->cellWidths[0], $top, $this->cellWidths[0], $top - $nameHeight);

            if ($item['sku'] !== '')
            {
                $x = $middle;
                $text = [];
                foreach ($this->string->split($item['sku'], 5, true, true) as $_value)
                {
                    $text[] = $_value;
                }
                foreach ($text as $part)
                {
                    $page->drawText(strip_tags(ltrim($part)), $this->cellWidths[0] + 5, $x, 'UTF-8');
                    $x -= 10;
                }
            }
            $page->drawLine($this->cellWidths[1], $top, $this->cellWidths[1], $top - $nameHeight);

            if ($item['name'] !== '')
            {
                $x = $middle;
                $text = [];
                foreach ($this->string->split($item['name'], 45, true, true) as $_value)
                {
                    $text[] = ucwords(strtolower($_value));
                }
                foreach ($text as $part)
                {
                    $page->drawText(strip_tags(ltrim($part)), $this->cellWidths[1] + 5, $x, 'UTF-8');
                    $x -= 10;
                }
            }
            $page->drawLine($this->cellWidths[2], $top, $this->cellWidths[2], $top - $nameHeight);

            $page->drawText($item['qty'], $this->cellWidths[2] + 12, $middle, 'UTF-8');
            $page->drawLine($this->cellWidths[3], $top, $this->cellWidths[3], $top - $nameHeight);

            $page->drawText(number_format($item['unit_price'], 2), $this->cellWidths[3] + 16, $middle, 'UTF-8');
            $page->drawLine($this->cellWidths[4], $top, $this->cellWidths[4], $top - $nameHeight);
            
            $page->drawText(number_format($item['gross_price'], 2), $this->cellWidths[4] + 16, $middle, 'UTF-8');
            $page->drawLine($this->cellWidths[5], $top, $this->cellWidths[5], $top - $nameHeight);

            $page->drawText(number_format($item['discount'], 2), $this->cellWidths[5] + 16, $middle, 'UTF-8');
            $page->drawLine($this->cellWidths[6], $top, $this->cellWidths[6], $top - $nameHeight);

            $page->drawText(number_format($item['subtotal'], 2), $this->cellWidths[6] + 16, $middle, 'UTF-8');
            $page->drawLine($this->cellWidths[7], $top, $this->cellWidths[7], $top - $nameHeight);

            $page->drawText(number_format($item['vat'], 2), $this->cellWidths[7] + 16, $middle, 'UTF-8');
            $page->drawLine($this->cellWidths[8], $top, $this->cellWidths[8], $top - $nameHeight);

            $page->drawText(number_format($item['net_total'], 2), $this->cellWidths[8] + 16, $middle, 'UTF-8');
            
            $top = $top - $nameHeight;

        }

        $this->y = $top - 10;
    }

    public function summaryBlock(\Zend_Pdf_Page $page, $invoice, $order) {
        $top = $this->y;

        $discountAmt = empty($order->getDiscountAmount()) ? 0 : $order->getDiscountAmount();
        $subtotal = $order->getSubtotal() - $discountAmt;
        $shippingFee = $order->getShippingAmount();
        $tax = $order->getTaxAmount();
        $totalAmt = $subtotal + $shippingFee;
        //$grandTotal = $totalAmt + $tax;
        $grandTotal = $invoice->getGrandTotal();

        $margin = 25;
        $down = 0 + $margin;
        $left = 0 + $margin;
        $right = $page->getWidth() - $margin;
        $up = $page->getHeight() - $margin;
        $center = $page->getWidth() / 2;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dictionary = $objectManager->create('Magento\Framework\App\Language\Dictionary');
        $translate = $dictionary->getDictionary('ar_SA');
        $rtlText = $objectManager->get(RtlTextHandler::class);

        $this->_setFontBold($page, 8);
        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($left, $top, $right - 100, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("Subtotal (Excl. VAT)", $left + 10, $top - 13, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate['Subtotal (Excl. VAT)']), $center + 53, $top - 13, 'UTF-8');

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($right - 100, $top, $right, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText(number_format($subtotal, 2) . " SAR", $right - 70, $top - 13, 'UTF-8');

        $top -= 20;

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($left, $top, $right - 100, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("Shipping Fee (Excl. VAT)", $left + 10, $top - 13, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Shipping Fee (Excl. VAT)"]), $center + 35, $top - 13, 'UTF-8');

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($right - 100, $top, $right, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText(number_format($shippingFee, 2) . " SAR", $right - 70, $top - 13, 'UTF-8');

        $top -= 20;

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($left, $top, $right - 100, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("Cash on delivery fees (Excl. VAT)", $left + 10, $top - 13, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Cash on delivery fees (Excl. VAT)"]), $center - 2, $top - 13, 'UTF-8');

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($right - 100, $top, $right, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("0.00 SAR", $right - 70, $top - 13, 'UTF-8');

        $top -= 20;

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($left, $top, $right - 100, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("Total Amount (Excl. VAT)", $left + 10, $top - 13, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Total Amount (Excl. VAT)"]), $center + 53, $top - 13, 'UTF-8');

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($right - 100, $top, $right, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText(number_format($totalAmt, 2) . " SAR", $right - 70, $top - 13, 'UTF-8');

        $top -= 20;

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($left, $top, $right - 100, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("Total VAT 15%", $left + 10, $top - 13, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Total VAT 15%"]), $center + 60, $top - 13, 'UTF-8');

        $page->setFillColor(new \Zend_Pdf_Color_Html('#ffffff'));
        $page->drawRectangle($right - 100, $top, $right, $top - 20);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText(number_format($tax, 2) . " SAR", $right - 70, $top - 13, 'UTF-8');

        $top -= 20;

        $this->_setFontBold($page, 11);
        $page->setFillColor(new \Zend_Pdf_Color_Html('#c0c4b4'));
        $page->drawRectangle($left, $top, $right - 100, $top - 40);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText($rtlText->reverseRtlText($translate["Grand Total"]), $center + 80, $top - 15, 'UTF-8');
        $page->drawText("Grand Total", $center + 90, $top - 30, 'UTF-8');

        $page->setFillColor(new \Zend_Pdf_Color_Html('#c0c4b4'));
        $page->drawRectangle($right - 100, $top, $right, $top - 40);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText(number_format($grandTotal) . " SAR", $right - 70, $top - 25, 'UTF-8');

        $top -= 50;

        $this->y = $top;
    }

    public function footerBlock(\Zend_Pdf_Page $page, $order) {
        $top = $this->y;

        $margin = 25;
        $down = 0 + $margin;
        $left = 0 + $margin;
        $right = $page->getWidth() - $margin;
        $up = $page->getHeight() - $margin;
        $center = $page->getWidth() / 2;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dictionary = $objectManager->create('Magento\Framework\App\Language\Dictionary');
        $rtlText = $objectManager->get(RtlTextHandler::class);
        $translate = $dictionary->getDictionary('ar_SA');

        $this->_setFontBold($page, 8);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("TERMS & CONDITIONS: ", $left + 5, $top - 15, 'UTF-8');

        $this->_setFontRegular($page, 7);
        $page->setFillColor(new \Zend_Pdf_Color_Html('#227DB3'));
        $page->drawText("( read online )", $left + 93, $top - 15, 'UTF-8');

        $this->_setFontBold($page, 8);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText($rtlText->reverseRtlText($translate["TERMS & CONDITIONS: "]), $right - 60, $top - 15, 'UTF-8');

        $this->_setFontRegular($page, 7);
        $page->setFillColor(new \Zend_Pdf_Color_Html('#227DB3'));
        $page->drawText($rtlText->reverseRtlText($translate["( read online )"]), $right - 125, $top - 15, 'UTF-8');

        $top -= 16;
        $this->_setFontRegular($page, 7);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawText("1. Accepting shipment from shipping company declares that the shipment (carton)", $left, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["1. Accepting shipment from shipping company declares that the shipment (carton)"]), $center - 15, $top - 15, 'UTF-8');
    
        $top -= 8;
        $page->drawText("is in good condition and it is sealed upon receiving.", $left, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["is in good condition and it is sealed upon receiving."]), $right - 55, $top - 15, 'UTF-8');

        $top -= 12;
        $page->drawText("2. All items are subject to Exchange with the following terms:", $left, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["2. All items are subject to Exchange with the following terms:"]), $center + 135, $top - 15, 'UTF-8');
    
        $top -= 8;
        $page->drawText("- All items must be accompanied by a copy of original receipt.", $left + 5, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["- All items must be accompanied by a copy of original receipt."]), $center + 90, $top - 15, 'UTF-8');

        $top -= 8;
        $page->drawText("- Item is in original condition & unopened.", $left + 5, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["- Item is in original condition & unopened."]), $center + 133, $top - 15, 'UTF-8');

        $top -= 8;
        $page->drawText("- Exchange can be done through our stores only after contacting our Yves Rocher", $left + 5, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["- Exchange can be done through our stores only after contacting our Yves Rocher"]), $center + 70, $top - 15, 'UTF-8');

        $top -= 8;
        $page->drawText("Customer Care team within 2 days of the date of delivery.", $left + 15, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Customer Care team within 2 days of the date of delivery."]), $center + 154, $top - 15, 'UTF-8');

        $top -= 25;
        $this->_setFontRegular($page, 12);
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0.2));
        $page->drawText("Telephone No. :", $center - 130, $top - 15, 'UTF-8');
        $this->addTeleLogo($page, $center - 45, $top - 4);
        $page->drawText($this->storeMobile, $center - 25, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Telephone No. :"]), $center + 70, $top - 15, 'UTF-8');

        $top -= 20;
        $page->drawText("Whatsapp No. :", $center - 130, $top - 15, 'UTF-8');
        $this->addWpLogo($page, $center - 45, $top - 4);
        $page->drawText($this->storeMobile, $center - 25, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Whatsapp No. :"]), $center + 70, $top - 15, 'UTF-8');

        $top -= 12;
        $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
        $page->drawLine($left, $top - 15, $right, $top - 15);

        $top -= 12;
        $this->_setFontRegular($page, 9);
        $page->drawText("Address 2091 Prince Majed Bin Abdullaziz - AlAnduls Dist. Office No. 603", $left + 5, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Address 2091 Prince Majed Bin Abdullaziz - AlAnduls Dist. Office No. 603"]), $center + 30, $top - 15, 'UTF-8');

        $top -= 12;
        $page->drawText("Jeddah P.O.Box 23326 - 8909 CR : 4030142664", $left + 5, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Jeddah P.O.Box 23326 - 8909 CR : 4030142664"]), $center + 65, $top - 15, 'UTF-8');

        $top -= 12;
        $page->drawText("Saudi Arabia", $left + 5, $top - 15, 'UTF-8');
        $page->drawText($rtlText->reverseRtlText($translate["Saudi Arabia"]), $center + 180, $top - 15, 'UTF-8');

        $this->_setFontRegular($page, 12);
        $page->setFillColor(new \Zend_Pdf_Color_Html('#85973E'));
        $page->drawText("www.yves-rocher.com.sa", $center - 65, $top - 15, 'UTF-8');

        $this->y = $top - 20;
    }

        /**
     * Set font as regular
     *
     * @param  \Zend_Pdf_Page $object
     * @param  int $size
     * @return \Zend_Pdf_Resource_Font
     */
    protected function _setFontRegular($object, $size="")
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('lib/internal/Tajawal/Tajawal-Regular.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

        /**
     * Set font as bold
     *
     * @param  \Zend_Pdf_Page $object
     * @param  int $size
     * @return \Zend_Pdf_Resource_Font
     */
    protected function _setFontBold($object, $size = "")
    {
        $font = \Zend_Pdf_Font::fontWithPath(
            $this->_rootDirectory->getAbsolutePath('lib/internal/Tajawal/Tajawal-Bold.ttf')
        );
        $object->setFont($font, $size);
        return $font;
    }

    protected function insertLogo(&$page, $store = null)
    {
        $this->y = $this->y ? $this->y : 815;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $filesystem = $objectManager->get('Magento\Framework\Filesystem');
        $mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);

        $image = $this->_scopeConfig->getValue(
            'sales/identity/logo',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        if ($image) {
            $imagePath = '/sales/store/logo/' . $image;
            // if ($this->fileStorageDatabase->checkDbUsage() &&
            //     !$this->_mediaDirectory->isFile($imagePath)
            // ) {
            //     $this->fileStorageDatabase->saveFileToFilesystem($imagePath);
            // }
            if ($mediaDirectory->isFile($imagePath)) {
                $image = \Zend_Pdf_Image::imageWithPath($mediaDirectory->getAbsolutePath($imagePath));
                $top = 830;

                $width = 125;
                $height = 28;

                $y1 = $top - $height - 35;
                $y2 = $top - 35;
                $x1 = 25;
                $x2 = $x1 + $width;

                //coordinates after transformation are rounded by Zend
                $page->drawImage($image, $x1, $y1, $x2, $y2);

                $this->y = $y1 - 10;
            }
        }
    }

    protected function addTeleLogo(&$page, $center, $top)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $assetRepo = $objectManager->get('Magento\Framework\View\Asset\Repository');

        $filePath = $assetRepo->createAsset('images/icons/telephone.png', ['area' => 'frontend'])->getSourceFile();

        if ($filePath != '') {
            $image = \Zend_Pdf_Image::imageWithPath($filePath);

            $width = 12;
            $height = 12;

            $y1 = $top - $height;
            $y2 = $top;
            $x1 = $center;
            $x2 = $x1 + $width;

            //coordinates after transformation are rounded by Zend
            $page->drawImage($image, $x1, $y1, $x2, $y2);
        }
    }

    protected function addWpLogo(&$page, $center, $top)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $assetRepo = $objectManager->get('Magento\Framework\View\Asset\Repository');

        $filePath = $assetRepo->createAsset('images/icons/whatsapp.png', ['area' => 'frontend'])->getSourceFile();

        if ($filePath != '') {
            $image = \Zend_Pdf_Image::imageWithPath($filePath);

            $width = 12;
            $height = 12;

            $y1 = $top - $height;
            $y2 = $top;
            $x1 = $center;
            $x2 = $x1 + $width;

            //coordinates after transformation are rounded by Zend
            $page->drawImage($image, $x1, $y1, $x2, $y2);
        }
    }

}