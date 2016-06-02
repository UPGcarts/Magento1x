<?php


class Upg_Payments_Block_Adminhtml_System_Config_Customerriskclass extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{

    protected $_customerGroupRenderer;
    protected $_riskClassRenderer;

    protected $helper;

    public function __construct()
    {
        $this->helper = Mage::helper('upg_payments');
        $this->_addAfter = false;
        $this->_addButtonLabel = $this->helper->__('Customer group risk class');

        parent::__construct();

    }

    protected function _prepareToRender()
    {

        $this->addColumn(
            'customer_group',
            array(
                'label' => $this->helper->__('Customer Group'),
                'style' => 'width:120px',
                'renderer' => $this->_getCustomerGroupRenderer(),
            )
        );

        $this->addColumn(
            'customer_riskclass',
            array(
                'label' => $this->helper->__('Risk Class'),
                'style' => 'width:120px',
                'renderer' => $this->_getRiskClassRenderer(),
            )
        );

    }

    public function _getCustomerGroupRenderer()
    {
        if (!$this->_customerGroupRenderer)
        {
            $this->_customerGroupRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_customergroup', '',
                array('is_render_to_js_template' => true)
            );
            $this->_customerGroupRenderer->setClass('customer_group_select');
            $this->_customerGroupRenderer->setExtraParams('style="width:120px"');
        }
        return $this->_customerGroupRenderer;
    }

    public function _getRiskClassRenderer()
    {
        if (!$this->_riskClassRenderer)
        {
            $this->_riskClassRenderer = $this->getLayout()->createBlock(
                'upg_payments/adminhtml_form_field_riskclass', '',
                array('is_render_to_js_template' => true)
            );
            $this->_riskClassRenderer->setClass('customer_riskclass_select');
            $this->_riskClassRenderer->setExtraParams('style="width:120px"');
        }
        return $this->_riskClassRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getCustomerGroupRenderer()->calcOptionHash($row->getData('customer_group')),
            'selected="selected"'
        );

        $row->setData(
            'option_extra_attr_' . $this->_getRiskClassRenderer()->calcOptionHash($row->getData('customer_riskclass')),
            'selected="selected"'
        );
    }
}