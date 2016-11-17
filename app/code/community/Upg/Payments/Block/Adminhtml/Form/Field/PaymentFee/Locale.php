<?php

class Upg_Payments_Block_Adminhtml_Form_Field_PaymentFee_Locale
    extends Mage_Core_Block_Html_Select
{
    public function _toHtml()
    {
        $this->setName($this->getInputName());
        if (! $this->getOptions()) {
            $this->setOptions(array(
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_DE,
                    'label' => Upg\Library\Locale\Codes::LOCALE_DE,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_EN,
                    'label' => Upg\Library\Locale\Codes::LOCALE_EN,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_ES,
                    'label' => Upg\Library\Locale\Codes::LOCALE_ES,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_FI,
                    'label' => Upg\Library\Locale\Codes::LOCALE_FI,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_FR,
                    'label' => Upg\Library\Locale\Codes::LOCALE_FR,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_IT,
                    'label' => Upg\Library\Locale\Codes::LOCALE_IT,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_NL,
                    'label' => Upg\Library\Locale\Codes::LOCALE_NL,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_PT,
                    'label' => Upg\Library\Locale\Codes::LOCALE_PT,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_RU,
                    'label' => Upg\Library\Locale\Codes::LOCALE_RU,
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\Locale\Codes::LOCALE_TU,
                    'label' => Upg\Library\Locale\Codes::LOCALE_TU,
                    'params' => array()
                )
            ));
        }
        return parent::_toHtml();
    }
}