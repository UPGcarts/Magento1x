<?php

class Upg_Payments_Block_Adminhtml_Form_Field_Customergroup extends Mage_Core_Block_Html_Select
{
    public function _toHtml()
    {
        $this->setName($this->getInputName());
        if (!$this->getOptions()) {
            foreach (Mage::getModel('adminhtml/system_config_source_customer_group')->toOptionArray() as $option) {
                $this->addOption($option['value'], addslashes($option['label']));
            }
        }
        return parent::_toHtml();
    }
}