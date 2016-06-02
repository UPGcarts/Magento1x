<?php


class Upg_Payments_Block_Adminhtml_System_Config_Currencysettings extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    protected $_currencyRenderer;

    protected $helper;

    public function __construct()
    {
        $this->helper = Mage::helper('upg_payments');
        $this->_addAfter = false;
        $this->_addButtonLabel = $this->helper->__('Add Currency to Store ID mapping');

        parent::__construct();

    }

    protected function _prepareToRender()
    {

        $this->addColumn(
            'currency_code',
            array(
                'label' => $this->helper->__('Currency'),
                'style' => 'width:120px',
                'renderer' => $this->_getCurrencyRenderer(),
            )
        );

        $this->addColumn(
            'store_id',
            array(
                'label' => $this->helper->__('Store ID'),
                'style' => 'width:120px',
            )
        );

    }

    public function _getCurrencyRenderer()
    {
        //$this->getLayout()->createBlock('payco_payment/adminhtml_form_field_currency');
        if (!$this->_currencyRenderer)
        {
            $this->_currencyRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_currency', '',
                array('is_render_to_js_template' => true)
            );
            $this->_currencyRenderer->setClass('currency_select');
            $this->_currencyRenderer->setExtraParams('style="width:120px"');
        }
        return $this->_currencyRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getCurrencyRenderer()->calcOptionHash($row->getData('currency_code')),
            'selected="selected"'
        );
    }

}