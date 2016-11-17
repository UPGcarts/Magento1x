<?php

class Upg_Payments_Block_Adminhtml_Totals_Invoice_TotalNew
    extends Mage_Core_Block_Abstract
{
    protected function _isFirstInvoiceForOrder($orderId)
    {
        $invoices = Mage::getModel('sales/order_invoice')
            ->getCollection()
            ->addFieldToFilter('order_id', $orderId)
            ->addFieldToSelect('order_id');

        return count($invoices) === 0;
    }

    public function initTotals()
    {
        $invoice = $this->getParentBlock()->getSource();
        $order = $invoice->getOrder();
        $orderId = $order->getId();

        if ($this->_isFirstInvoiceForOrder($orderId)) {
            $fee = $order->getData('upg_payments_fee');

            if (! is_null($fee)) {
                $total = new Varien_Object(array(
                    'code' => 'upg_payments',
                    'field' => 'upg_payments_fee',
                    'value' => $fee,
                    'label' => $this->__("Payment Fee")
                ));

                $this->getParentBlock()->addTotal($total);
            }
        }

        return $this;
    }
}
