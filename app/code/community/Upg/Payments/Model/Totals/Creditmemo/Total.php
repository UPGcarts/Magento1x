<?php

class Upg_Payments_Model_Totals_Creditmemo_Total
    extends Mage_Sales_Model_Order_Creditmemo_Total_Abstract
{
    protected function _existingCreditmemoCollect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        $refundedFee = $creditmemo->getData('upg_payments_fee_refunded');
        $refundedBaseFee = $creditmemo->getData('upg_payments_base_fee_refunded');
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $refundedFee);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $refundedBaseFee);
    }

    protected function _newCreditmemoCollect(Mage_Sales_Model_Order_Creditmemo $creditmemo, $invoice)
    {
        $fee = $invoice->getData('upg_payments_fee');
        $base_fee = $invoice->getData('upg_payments_base_fee');

        $totalRefundedFee = $invoice->getData('upg_payments_fee_total_refunded');
        $totalRefundedBaseFee = $invoice->getData('upg_payments_base_fee_total_refunded');
        $remainingFee = $fee - $totalRefundedFee;
        $remainingBaseFee = $base_fee - $totalRefundedBaseFee;

        if (!is_null($fee) && !is_null($base_fee)) {
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $remainingFee);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $remainingBaseFee);
        }
    }

    protected function _updateQtyOrCreateCreditmemoCollect(Mage_Sales_Model_Order_Creditmemo $creditmemo, $invoice, $paymentFee)
    {
        $fee = $invoice->getData('upg_payments_fee');
        $base_fee = $invoice->getData('upg_payments_base_fee');

        if (!is_null($fee) && !is_null($base_fee)) {
            $currency = Mage::helper('upg_payments/currency');
            $paymentBaseFee = $currency->convertToBaseCurrency($paymentFee);

            $totalRefundedFee = $invoice->getData('upg_payments_fee_total_refunded');
            $totalRefundedBaseFee = $invoice->getData('upg_payments_base_fee_total_refunded');
            $remainingFee = $fee - $totalRefundedFee;
            $remainingBaseFee = $base_fee - $totalRefundedBaseFee;

            if ($remainingFee >= $paymentFee && $remainingBaseFee >= $paymentBaseFee) {
                $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $paymentFee);
                $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $paymentBaseFee);
                $creditmemo->setData('upg_payments_fee_refunded', $paymentFee);
                $creditmemo->setData('upg_payments_base_fee_refunded', $paymentBaseFee);
            }
            else {
                throw new Exception("Tried to credit more than the remaining payment fee [Fee: $paymentFee] [Remaining fee: $remainingFee]");
            }
        }
    }

    public function collect(Mage_Sales_Model_Order_Creditmemo $creditmemo)
    {
        if ($creditmemo->getOrder()->getPayment()->getMethod() !== 'upg_payments') return;

        $refundedFee = $creditmemo->getData('upg_payments_fee_refunded');
        $refundedBaseFee = $creditmemo->getData('upg_payments_base_fee_refunded');

        if (!is_null($refundedFee) && !is_null($refundedBaseFee)) {
            $this->_existingCreditmemoCollect($creditmemo);
            return $this;
        }

        $invoice = $creditmemo->getInvoice();
        if (is_null($invoice->getId())) {
            throw new Exception("Creditmemo #{$creditmemo->getIncrementId()} must be associated with an invoice.");
        }

        // Used in observer setInvoiceTotalPaymentFeeRefunded
        $invoice->setData('upg_creditmemo', $creditmemo);

        $creditmemoPost = Mage::app()->getRequest()->getPost('creditmemo');
        $paymentFeePost = isset($creditmemoPost['payment_fee']) ? $creditmemoPost['payment_fee'] : null;

        if (is_null($paymentFeePost)) {
            $this->_newCreditmemoCollect($creditmemo, $invoice);
            return $this;
        }

        $this->_updateQtyOrCreateCreditmemoCollect($creditmemo, $invoice, $paymentFeePost);
        return $this;
    }
}