<?php

class Upg_Payments_Helper_PaymentFee
    extends Mage_Core_Helper_Abstract
{
    public function calcFee($feeType, $feeAmount, $grandTotal)
    {
        switch ($feeType) {
            case 'percent':
                return $this->_calcPercentageFee($feeAmount, $grandTotal);
            case 'flat':
                return $feeAmount;
            default:
                throw new Exception("Invalid fee type provided: $feeType");
        }
    }

    protected function _calcPercentageFee($feeAmount, $grandTotal)
    {
        return round(($feeAmount / 100) * $grandTotal, 2);
    }

    public function addHostedPagesTextToRequest($request, $paymentFee)
    {
        $hostedPagesText = new Upg\Library\Request\Objects\HostedPagesText();
        $hostedPagesText->setPaymentMethodType($paymentFee['paymentfee_paymentmethod'])
            ->setDescription($paymentFee['paymentfee_text'])
            ->setLocale($paymentFee['paymentfee_locale'])
            ->setFee($paymentFee['paymentfee_fee']);
        $request->setHostedPagesTexts($hostedPagesText);
    }

    public function addPaymentFeeToQuote($transaction)
    {
        if ( ($paymentFees = $transaction->getPaymentFees()) !== null) {
            $paymentMethod = $transaction->getPaymentMethod();
            foreach (unserialize($paymentFees) as $paymentFee) {
                if ($paymentFee['paymentfee_paymentmethod'] === $paymentMethod) {
                    $quote = $this->getQuoteByReservedOrderId($transaction->getData('order_ref'));
                    // fee is in pennies so divide by 100
                    $quote->setData('upg_payments_fee', $paymentFee['paymentfee_fee'] / 100);
                    $quote->setData('upg_payments_base_fee', $paymentFee['paymentfee_basefee'] / 100);
                    $quote->save();
                }
            }
        }
    }

    public function getQuoteByReservedOrderId($reservedOrderId)
    {
        return Mage::getModel('sales/quote')
            ->getCollection()
            ->addFieldToFilter('reserved_order_id', $reservedOrderId)
            ->getFirstItem();
    }

    public function addWarning($message)
    {
        Mage::getSingleton('core/session')->addWarning($message);
    }

}