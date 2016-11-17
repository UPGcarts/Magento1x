<?php

class Upg_Payments_Block_Adminhtml_Form_Field_PaymentFee_TextArea
    extends Mage_Core_Block_Abstract
{
    public function _toHtml()
    {
        $this->setName($this->getInputName());
        $column = $this->getColumn();
        
        return '<textarea name="' . $this->getInputName() . '" ' .
        ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
        (isset($column['class']) ? $column['class'] : 'input-text') . '"'.
        (isset($column['style']) ? ' style="'.$column['style'] . '"' : '') . '>#{' . $this->getColumnName() . '}</textarea>';
    }
}
