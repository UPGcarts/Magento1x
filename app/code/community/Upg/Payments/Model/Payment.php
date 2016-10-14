<?php

class Upg_Payments_Model_Payment extends Mage_Payment_Model_Method_Abstract
{

    protected $_code = 'upg_payments';
    protected $_formBlockType = 'upg_payments/payment_form';
    protected $_infoBlockType = 'upg_payments/payment_info';
    protected $_canAuthorize            = true;
    protected $_canCapture = true;
    protected $_canCapturePartial = true;
    protected $_canRefund = true;
    protected $_canRefundInvoicePartial = true;
    protected $_canUseForMultishipping = false;
    protected $_canUseInternal = false;
    protected $_canVoid = false;
    protected $_canSaveCc = false;
    protected $_isGateway                   = true;
    protected $_canOrder                    = true;

    public $autoCaptureMnsFake = array('BILL','BILL_SECURE','DD');

    public function _construct()
    {
        parent::_construct();
    }

    public function authorize(Varien_Object $payment, $amount)
    {
        try {
            $order = $payment->getOrder();
            $transaction = Mage::getModel('upg_payments/transaction')->getCollection()
                ->addFieldToFilter('order_ref', $order->getIncrementId())
                ->getFirstItem();

            $request = Mage::helper('upg_payments/transaction')->reserveTransaction($order, $transaction);
            $response = Mage::helper('upg_payments/transaction')->sendReserveTransaction($request, $transaction, $order);

            $responseData = $response->getAllData();
            if(array_key_exists('redirectUrl', $responseData)) {
                //ok as the order is not complete due to redirect set the transaction as pending for the moment
                $payment->setIsTransactionPending(1);
            }else{
                $payment->addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_ORDER, null, false, "Adding order transaction");
                $payment->setIsTransactionClosed(0);
                $payment->setIsTransactionApproved(true);
                $this->createPaidMnsMessage($order);
            }
            $payment->setTransactionId($order->getIncrementId());
        }catch (Exception $e) {
            Mage::throwException($e->getMessage());
        }

    }

    public function createPaidMnsMessage(Mage_Sales_Model_Order $order, $delay = 1)
    {
        $transaction = Mage::getModel('upg_payments/transaction')->getCollection()
            ->addFieldToFilter('order_ref', $order->getIncrementId())
            ->getFirstItem();

        if($transaction->getAutocapture() && in_array($transaction->getPaymentMethod(), $this->autoCaptureMnsFake)) {
            //ok for bill and dd create a fake mns message if autocapture is enabled
            $mns = Mage::getModel('upg_payments/message')->setData(
                array(
                    'merchant_id' => '',
                    'store_id' => '',
                    'order_id' => $order->getIncrementId(),
                    'capture_id' => $order->getIncrementId(),
                    'merchant_reference' => '',
                    'payment_reference' => '',
                    'user_id' => '',
                    'amount' => '',
                    'currency' => '',
                    'transaction_status' => 'PAID',
                    'order_status' => 'PAID',
                    'additional_data' => '',
                    'version' => '2.0',
                    'mns_timestamp' => 1,
                    'mns_delay_processing' => $delay,
                )
            );

            $mns->save();
        }
    }

    /**
     * Fetch transaction info
     *
     * @param Mage_Payment_Model_Info $payment
     * @param string $transactionId
     * @return array
     */
    public function fetchTransactionInfo(Mage_Payment_Model_Info $payment, $transactionId)
    {
        echo $transactionId;exit;
        return parent::fetchTransactionInfo();
    }

    public function capture(Varien_Object $payment, $amount)
    {
        $order = $payment->getOrder();
        $autocaptureFlag = Mage::registry('payco_autocaptue_invoice');
        if ($autocaptureFlag == $order->getIncrementId()) {
            //lets simulate a online capture so refund can work
            //setting the transaction id to the order increment id
            $payment->setTransactionId($order->getUpgPaymentsUtInvoiceId());
        } else {
            $payment->setTransactionId($order->getUpgPaymentsUtInvoiceId());
            //now do the call
            $request = Mage::helper('upg_payments/transaction')->getCaptureTransaction($order,
                $payment->getTransactionId(), $amount);
            // Response error code isn't checked as an exception will be thrown
            $response = Mage::helper('upg_payments/transaction')->sendCaptureTransaction($request, $order);
        }

        return $this;
    }

    public function processBeforeRefund($invoice, $payment)
    {

        return parent::processBeforeRefund($invoice, $payment);
    }

    public function refund(Varien_Object $payment, $amount)
    {
        $refundTransactionId = $payment->getRefundTransactionId();
        if ($refundTransactionId) {
            try {
                $order = $payment->getOrder();
                $request = Mage::helper('upg_payments/transaction')->getRefundTransaction($order, $refundTransactionId, $amount);
                Mage::helper('upg_payments/transaction')->sendRefundTransaction($request, $order);
            }catch (Exception $e){
                throw $e;
            }
        } else {
            Mage::throwException(Mage::helper('upg_payments')->__('Impossible to issue a refund transaction because the capture transaction does not exist.'));
        }

        return $this;
    }

    public function getConfigPaymentAction()
    {
        //force it to be authorize only for the moment
        return Mage_Payment_Model_Method_Abstract::ACTION_AUTHORIZE;
    }

    public function isAvailable($quote = null)
    {
        $isAvailable = Mage::getStoreConfig('payment/upg/enabled');
        if($isAvailable) {
            return true;
        }else{
            return false;
        }
    }

    public function getTitle()
    {
        return Mage::getStoreConfig('payment/upg/title');
    }

    public function getOrderPlaceRedirectUrl()
    {
        $quote = Mage::getModel('sales/quote');
        $quote->load(Mage::getSingleton('checkout/session')->getLastQuoteId());

        $transaction = Mage::getModel('upg_payments/transaction')->getCollection()
            ->addFieldToFilter('order_ref', $quote->getReservedOrderId())
            ->getFirstItem();


        $redirectUrl = $transaction->getRedirectUrl();

        if(!empty($redirectUrl)) {
            return $redirectUrl;
        }
        return null;
    }

}