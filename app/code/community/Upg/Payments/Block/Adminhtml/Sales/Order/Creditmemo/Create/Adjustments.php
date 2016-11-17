<?php

class Upg_Payments_Block_Adminhtml_Sales_Order_Creditmemo_Create_Adjustments
    extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Create_Adjustments
{
    public function getRemainingPaymentFee()
    {
        $invoiceId = Mage::app()->getRequest()->getParam('invoice_id');
        if (is_null($invoiceId)) return 0;  // Creditmemo not created from invoice
        $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId);
        $fee = $invoice->getData('upg_payments_fee');
        if (is_null($fee)) return 0;  // Invoice has no fee attached
        $totalRefundedFee = $invoice->getData('upg_payments_fee_total_refunded');
        return $fee - $totalRefundedFee;
    }
}