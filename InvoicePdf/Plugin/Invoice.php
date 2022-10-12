<?php

namespace Albatool\InvoicePdf\Plugin;

use Zend\Barcode\Barcode;
class Invoice
{
  /**
   * Add Invoice # barcode to invoice PDF.
   * @param \Magento\Sales\Model\Order\Pdf\Invoice $subject
   * @param \Zend_Pdf_Page $page
   * @param string $text
   */
  public function beforeInsertDocumentNumber($subject, $page, $text) {
    $docHeader = $subject->getDocHeaderCoordinates();

    $image = $this->_generateBarcode($text);

    //Convert barcode px dimensions to points
    $width = $image->getPixelWidth() * 72 / 96;
    $height = $image->getPixelHeight() * 72 / 96;

    $page->drawImage($image, $docHeader[2] - $width, $docHeader[1] - $height, $docHeader[2], $docHeader[1]);
  }

  /**
   * @param string $text
   * @return \Zend_Pdf_Resource_Image_Png
   */
  protected function _generateBarcode($text) {
    $config = new \Zend_Config([
      'barcode' => 'code128',
      'barcodeParams' => [
        'text' => $this->_extractInvoiceNumber($text),
        'drawText' => false
      ],
      'renderer' => 'image',
      'rendererParams' => ['imageType' => 'png']
    ]);

    $barcodeResource = Barcode::factory($config)->draw();

    ob_start();
    imagepng($barcodeResource);
    $barcodeImage = ob_get_clean();

    $image = new \Zend_Pdf_Resource_Image_Png('data:image/png;base64,'.base64_encode($barcodeImage));

    return $image;
  }

  /**
   * Strip "Invoice # " from input string.
   * @param string $text
   * @return string
   */
  protected function _extractInvoiceNumber($text) {
    preg_match("/.*#\s(.*)/", $text, $matches);

    return $matches[1];
  }
}