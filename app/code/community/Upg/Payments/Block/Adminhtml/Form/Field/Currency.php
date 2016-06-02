<?php

class Upg_Payments_Block_Adminhtml_Form_Field_Currency extends Mage_Core_Block_Html_Select
{

    private function _getCurrencies()
    {
        $currencies = Mage::app()->getLocale()->getOptionCurrencies();
        return $currencies;
    }

    public function _toHtml()
    {
        $this->setName($this->getInputName());
        if (!$this->getOptions()) {
            foreach ($this->_getCurrencies() as $option) {
                $this->addOption($option['value'], addslashes($option['label']));
            }
        }
        return parent::_toHtml();
    }

}