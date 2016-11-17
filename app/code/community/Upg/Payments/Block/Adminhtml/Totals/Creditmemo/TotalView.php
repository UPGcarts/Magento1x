<?php

class Upg_Payments_Block_Adminhtml_Totals_Creditmemo_TotalView
    extends Mage_Core_Block_Abstract
{
    public function initTotals()
    {
        $creditmemo = $this->getParentBlock()->getSource();
        $fee = $creditmemo->getData('upg_payments_fee_refunded');

        if (! is_null($fee)) {
            $total = new Varien_Object(array(
                'code' => 'upg_payments',
                'field' => 'upg_payments_fee',
                'value' => $fee,
                'label' => $this->__('Payment Fee Refund')
            ));

            $this->getParentBlock()->addTotal($total);
        }

        return $this;
    }
}
