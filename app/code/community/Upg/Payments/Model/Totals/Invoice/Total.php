<?php

class Upg_Payments_Model_Totals_Invoice_Total
    extends Mage_Sales_Model_Order_Invoice_Total_Abstract
{
    protected function _isFirstInvoiceForOrder($orderId)
    {
        $invoices = Mage::getModel('sales/order_invoice')
            ->getCollection()
            ->addFieldToFilter('order_id', $orderId)
            ->addFieldToSelect('order_id');

        return count($invoices) === 0;
    }

    protected function _newInvoiceCollect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        $fee = $invoice->getOrder()->getData('upg_payments_fee');
        $base_fee = $invoice->getOrder()->getData('upg_payments_base_fee');

        if (!is_null($fee) && !is_null($base_fee)) {
            $invoice->setData('upg_payments_fee', $fee);
            $invoice->setData('upg_payments_base_fee', $base_fee);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $fee);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $base_fee);
        }
    }

    protected function _viewInvoiceCollect(Mage_Sales_Model_Order_Invoice $invoice) {
        $fee = $invoice->getData('upg_payments_fee');
        $base_fee = $invoice->getData('upg_payments_base_fee');

        if (!is_null($fee) && !is_null($base_fee)) {
            $invoice->setGrandTotal($invoice->getGrandTotal() + $fee);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $base_fee);
        }
    }

    public function collect(Mage_Sales_Model_Order_Invoice $invoice)
    {
        if ($invoice->getOrder()->getPayment()->getMethod() !== 'upg_payments') return;

        parent::collect($invoice);

        $orderId = $invoice->getOrder()->getId();

        if ($this->_isFirstInvoiceForOrder($orderId)) {
            $this->_newInvoiceCollect($invoice);
        }
        else {
            $this->_viewInvoiceCollect($invoice);
        }
    }
}