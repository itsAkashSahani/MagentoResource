<?php

namespace Albatool\CustomAttributes\Model;

class Currency extends \Magento\Directory\Model\Currency
{
    public function formatTxt($price, $options = [])
    {
        if (!is_numeric($price)) {
            $price = $this->_localeFormat->getNumber($price);
        }
        $price = sprintf("%F", $price);
        $currency_code = " ".$this->getCode();

        return $this->_localeCurrency->getCurrency($currency_code)->toCurrency($price, $options);
    }

}