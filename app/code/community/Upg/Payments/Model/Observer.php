<?php

class Upg_Payments_Model_Observer
    extends Mage_Core_Model_Abstract
{
    public function addInvoiceToUpdateTransactionTable($observer)
    {
        $helper = Mage::helper('upg_payments');
        $configHelper = Mage::helper('upg_payments/config');

        $invoice = $observer->getInvoice();
        $orderId = $invoice->getOrder()->getId();
        $orderIncrementId = $invoice->getOrder()->getIncrementId();
        $utInvoiceId = $orderId . ':' . time();
        $invoice->setUpgPaymentsUtInvoiceId($utInvoiceId);
        $invoice->getOrder()->setUpgPaymentsUtInvoiceId($utInvoiceId);  // This gets used as the captureID

        $transaction = Mage::getModel('upg_payments/transaction')
            ->getCollection()
            ->addFieldToFilter('order_ref', $orderIncrementId)
            ->getFirstItem();

        if (is_null($transaction->getId())) {
            $helper->log("No entry found in the upg_payments_transactions table for order #$orderIncrementId");
            return;
        }

        $paymentMethod = $transaction->getPaymentMethod();

        $isUpdateTransactionPaymentMethod = (
            $paymentMethod == Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_BILL ||
            $paymentMethod == Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_BILL_SECURE ||
            $paymentMethod == Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_DD
        );

        if ($configHelper->getUpdateTransactionEnabled() == 1 && $isUpdateTransactionPaymentMethod) {
            // Create updateTransaction job to be run later by cron
            Mage::getModel('upg_payments/updateTransaction')
                ->setOrderId($orderId)
                ->setUtTimestamp(time())
                ->setUtInvoiceId($utInvoiceId)
                ->save();
        }
    }

    public function resetCustomFeeAddedFlag()
    {
        Mage::getSingleton('upg_payments/totals_quote_feeAddedFlag')->reset();
    }

    public function setCreditmemoPaymentFee($observer)
    {
        $creditmemo = $observer->getCreditmemo();

        $invoice = $creditmemo->getInvoice();
        if (is_null($invoice->getId())) {
            return;
        }

        $fee = $invoice->getData('upg_payments_fee');
        $base_fee = $invoice->getData('upg_payments_base_fee');
        $creditmemo->setData('upg_payments_fee', $fee);
        $creditmemo->setData('upg_payments_base_fee', $base_fee);
    }

    public function setInvoiceTotalPaymentFeeRefunded($observer)
    {
        $invoice = $observer->getInvoice();

        $creditmemo = $invoice->getData('upg_creditmemo');
        if (is_null($creditmemo)) {
            return;
        }

        $refundedFee = $creditmemo->getData('upg_payments_fee_refunded');
        $refundedBaseFee = $creditmemo->getData('upg_payments_base_fee_refunded');
        $totalRefundedFee = $invoice->getData('upg_payments_fee_total_refunded');
        $totalRefundedBaseFee = $invoice->getData('upg_payments_base_fee_total_refunded');
        $invoice->setData('upg_payments_fee_total_refunded', $totalRefundedFee + $refundedFee);
        $invoice->setData('upg_payments_base_fee_total_refunded', $totalRefundedBaseFee + $refundedBaseFee);
    }
}