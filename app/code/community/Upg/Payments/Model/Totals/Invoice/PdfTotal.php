<?php

class Upg_Payments_Model_Totals_Invoice_PdfTotal
    extends Mage_Sales_Model_Order_Pdf_Total_Default
{
    public function getTotalsForDisplay()
    {
        $order = $this->getOrder();
        $paymentFee = $order->getData('upg_payments_fee');

        return array(array(
            'label' => 'Payment Fee',
            'amount' => Mage::helper('core')->currency($paymentFee, true, false),
            'font_size' => 10
        ));
    }
}