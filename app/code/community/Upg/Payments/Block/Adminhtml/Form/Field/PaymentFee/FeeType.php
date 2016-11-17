<?php

class Upg_Payments_Block_Adminhtml_Form_Field_PaymentFee_FeeType
    extends Mage_Core_Block_Html_Select
{
    public function _toHtml()
    {
        $helper = Mage::helper('upg_payments');

        $this->setName($this->getInputName());
        if (! $this->getOptions()) {
            $this->setOptions(array(
                array(
                    'value' => 'percent',
                    'label' => $helper->__('Percent'),
                    'params' => array()
                ),
                array(
                    'value' => 'flat',
                    'label' => $helper->__('Flat'),
                    'params' => array()
                )
            ));
        }
        return parent::_toHtml();
    }
}