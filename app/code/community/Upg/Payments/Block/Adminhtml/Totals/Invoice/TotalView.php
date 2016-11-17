<?php

class Upg_Payments_Block_Adminhtml_Totals_Invoice_TotalView
    extends Mage_Core_Block_Abstract
{
    public function initTotals()
    {
        $invoice = $this->getParentBlock()->getSource();
        $fee = $invoice->getData('upg_payments_fee');

        if (! is_null($fee)) {
            $total = new Varien_Object(array(
                'code' => 'upg_payments',
                'field' => 'upg_payments_fee',
                'value' => $fee,
                'label' => $this->__('Payment Fee')
            ));

            $this->getParentBlock()->addTotal($total);
        }

        return $this;
    }
}