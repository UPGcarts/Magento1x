<?php

class Upg_Payments_Block_Adminhtml_System_Config_CustomPaymentFees
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_paymentFeeRenderer;
    protected $_currencyRenderer;
    protected $_localeRenderer;
    protected $_feeTypeRenderer;
    protected $_textRenderer;

    protected $helper;

    public function __construct()
    {
        $this->helper = Mage::helper('upg_payments');
        $this->_addAfter = false;
        $this->_addButtonLabel = $this->helper->__('Add custom payment fee');

        parent::__construct();
    }

    protected function _prepareToRender()
    {
        $this->addColumn(
            'paymentfee_paymentmethod',
            array(
                'label' => $this->helper->__('Payment Method'),
                'style' => '',
                'renderer' => $this->_getPaymentMethodRenderer(),
            )
        );
        
        $this->addColumn(
            'paymentfee_currency',
            array(
                'label' => $this->helper->__('Currency'),
                'style' => '',
                'renderer' => $this->_getCurrencyRenderer(),
            )
        );
        
        $this->addColumn(
            'paymentfee_locale',
            array(
                'label' => $this->helper->__('Locale'),
                'style' => '',
                'renderer' => $this->_getLocaleRenderer(),
            )
        );
        
        $this->addColumn(
            'paymentfee_feeamount',
            array(
                'label' => $this->helper->__('Fee Amount'),
                'style' => 'width:120px'
            )
        );
        
        $this->addColumn(
            'paymentfee_feetype',
            array(
                'label' => $this->helper->__('Fee Type'),
                'style' => '',
                'renderer' => $this->_getFeeTypeRenderer(),
            )
        );
        
        $this->addColumn(
            'paymentfee_text',
            array(
                'label' => $this->helper->__('Text'),
                'style' => 'height: 60px',
                'renderer' => $this->_getTextRenderer(),
            )
        );
    }

    protected function _getPaymentMethodRenderer()
    {
        if (! $this->_paymentFeeRenderer) {
            $this->_paymentFeeRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_paymentFee_paymentMethod',
                '',
                array('is_render_to_js_template' => true)
            );
            $this->_paymentFeeRenderer->setClass('paymentfee_paymentmethod_select');
            $this->_paymentFeeRenderer->setExtraParams('style="width:120px"');
        }
        return $this->_paymentFeeRenderer;
    }
    
    protected function _getCurrencyRenderer()
    {
        if (! $this->_currencyRenderer) {
            $this->_currencyRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_currency',
                '',
                array('is_render_to_js_template' => true)
            );
            $this->_currencyRenderer->setClass('paymentfee_currency_select');
            $this->_currencyRenderer->setExtraParams('style="width:120px"');
        }
        return $this->_currencyRenderer;
    }

    protected function _getLocaleRenderer()
    {
        if (! $this->_localeRenderer) {
            $this->_localeRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_paymentFee_locale',
                '',
                array('is_render_to_js_template' => true)
            );
            $this->_localeRenderer->setClass('paymentfee_locale_select');
            $this->_localeRenderer->setExtraParams('style="width:120px"');
        }
        return $this->_localeRenderer;
    }

    protected function _getFeeTypeRenderer()
    {
        if (! $this->_feeTypeRenderer) {
            $this->_feeTypeRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_paymentFee_feeType',
                '',
                array('is_render_to_js_template' => true)
            );
            $this->_feeTypeRenderer->setClass('paymentfee_feetype_select');
            $this->_feeTypeRenderer->setExtraParams('style="width:120px"');
        }
        return $this->_feeTypeRenderer;
    }

    protected function _getTextRenderer()
    {
        if (! $this->_textRenderer) {
            $this->_textRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_paymentFee_textArea',
                '',
                array('is_render_to_js_template' => true)
            );
            $this->_textRenderer->setClass('paymentfee_text');
        }
        return $this->_textRenderer;
    }
    
    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getPaymentMethodRenderer()->calcOptionHash($row->getData('paymentfee_paymentmethod')),
            'selected="selected"'
        );

        $row->setData(
            'option_extra_attr_' . $this->_getCurrencyRenderer()->calcOptionHash($row->getData('paymentfee_currency')),
            'selected="selected"'
        );
        
        $row->setData(
            'option_extra_attr_' . $this->_getLocaleRenderer()->calcOptionHash($row->getData('paymentfee_locale')),
            'selected="selected"'
        );
        
        $row->setData(
            'option_extra_attr_' . $this->_getFeeTypeRenderer()->calcOptionHash($row->getData('paymentfee_feetype')),
            'selected="selected"'
        );
        
        $row->setData(
            'paymentfee_text',
            $row->getData('paymentfee_text')
        );
    }
}