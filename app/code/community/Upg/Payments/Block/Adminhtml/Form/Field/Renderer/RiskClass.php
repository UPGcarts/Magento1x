<?php

class Upg_Payments_Block_Adminhtml_Form_Field_Renderer_RiskClass
    extends Varien_Data_Form_Element_Select
{
    public function getElementHtml()
    {
        if (is_null($this->getValue())) {
            $this->setValue(1);
        }
        return parent::getElementHtml();
    }
}