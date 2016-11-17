<?php

class Upg_Payments_Block_Adminhtml_Form_Field_PaymentFee_PaymentMethod
    extends Mage_Core_Block_Html_Select
{
    public function _toHtml()
    {
        $this->setName($this->getInputName());
        if (! $this->getOptions()) {
            $this->setOptions(array(
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_CC,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_CC),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_DD,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_DD),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_PAYPAL,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_PAYPAL),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_BILL,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_BILL),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_BILL_SECURE,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_BILL_SECURE),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_COD,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_COD),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_IDEAL,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_IDEAL),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_INSTALLMENT,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_INSTALLMENT),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_PAYCO_WALLET,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_PAYCO_WALLET),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_PREPAID,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_PREPAID),
                    'params' => array()
                ),
                array(
                    'value' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_SU,
                    'label' => Mage::helper('upg_payments')->__(Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_SU),
                    'params' => array()
                )
            ));
        }
        return parent::_toHtml();
    }
}