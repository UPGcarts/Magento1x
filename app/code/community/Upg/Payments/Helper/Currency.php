<?php

class Upg_Payments_Helper_Currency
    extends Mage_Core_Helper_Abstract
{
    public function convertToBaseCurrency($amount)
    {
        $baseCurrencyCode = Mage::app()->getStore()->getBaseCurrencyCode();
        $currentCurrencyCode = Mage::app()->getStore()->getCurrentCurrencyCode();

        if ($baseCurrencyCode === $currentCurrencyCode) return $amount;

        try {
            return Mage::helper('directory')->currencyConvert($amount, $currentCurrencyCode, $baseCurrencyCode);
        }
        catch (Exception $e) {
            Mage::helper('upg_payments')->log($e->getMessage());
            throw $e;
        }
    }
}