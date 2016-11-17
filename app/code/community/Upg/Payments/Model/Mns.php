<?php require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

class Upg_Payments_Model_Mns
{
    public function process()
    {
        $collection = Mage::getModel('upg_payments/message')->getCollection()
            ->addFieldToFilter('mns_processed', array('eq' => 0))
            ->addFieldToFilter('mns_error_processing', array('eq' => 0))
            ->addFieldToFilter('mns_delay_processing', array('eq' => 0))
            ->setOrder('mns_timestamp','ASC');

        foreach($collection as $message)
        {
            /**
             * @var Upg_Payments_Model_Message $message
             */
            $mnsOrderStatus = trim(strtoupper($message->getOrderStatus()));
            $mnsTransactionStatus = trim(strtoupper($message->getTransactionStatus()));

            $order = Mage::getModel('sales/order')->loadByIncrementId($message->getOrderId());

            if(!$order->getId() > 0) {
                Mage::helper('upg_payments')->log("Could not find order ".$message->getOrderId());
                self::markMnsAsFailed($message);
                continue;
            }

            $method = $order->getPayment()->getMethod();

            if($method != "upg_payments") {
                Mage::helper('upg_payments')->log("Notification will not be processed as order ".$message->getOrderId()." is not processed by upg_payments.");
                self::markMnsAsFailed($message);
                continue;
            }

            $processed = false;
            $orderStatusProcess = false;

            if(!empty($mnsTransactionStatus)) {
                switch ($mnsTransactionStatus) {
                    case 'FRAUDCANCELLED':
                        self::fraudCancelled($order);
                        $processed = true;
                        break;
                    case 'CANCELLED':
                    case 'EXPIRED':
                        self::cancelled($order);
                        $processed = true;
                        break;
                    case 'NEW':
                    case 'ACKNOWLEDGEPENDING':
                        $processed = true;
                        break;
                    case 'FRAUDPENDING':
                        self::fraudPending($order);
                        $processed = true;
                        break;
                    case 'CIAPENDING':
                        self::ciaPending($order);
                        $processed = true;
                        break;
                    case 'MERCHANTPENDING':
                        self::merchantPending($order, $message);
                        $processed = true;
                        break;
                    case 'INPROGRESS':
                        $processed = true;
                        break;
                    case 'DONE':
                        $processed = true;
                        break;
                    default:
                        $processed = false;
                        break;
                }
            }

            if(!empty($mnsOrderStatus) && ($orderStatusProcess || !$processed)) {
                switch ($mnsOrderStatus) {
                    case 'PAID':
                        self::paid($order);
                        $processed = true;
                        break;
                    case 'PAYPENDING':
                        $processed = true;
                        break;
                    case 'PAYMENTFAILED':
                        self::paymentFailed($order);
                        $processed = true;
                        break;
                    case 'CHARGEBACK':
                        self::chargeBack($order);
                        $processed = true;
                        break;
                    case 'CLEARED':
                        self::cleared($order);
                        $processed = true;
                        break;
                    case 'CPM_MANAGED':
                    case 'INDUNNING':
                        self::inDunning($order);
                        break;
                    default:
                        $processed = false;
                        break;
                }
            }

            if($processed) {
                self::markMnsAsProcessed($message);
            }else{
                self::markMnsAsFailed($message);
            }
        }

        //ok now unlock delayed transactions
        //mns_delay_processing
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $write->update(
            Mage::getModel('upg_payments/message')->getResource()->getTable('upg_payments/mns_message'),
            array("mns_delay_processing" => 0),
            "mns_delay_processing=1"
        );
    }

    public static function markMnsAsProcessed(Upg_Payments_Model_Message $message)
    {
        $message->setMnsProcessed(1)->save();
    }

    public static function markMnsAsFailed(Upg_Payments_Model_Message $message)
    {
        $message->setMnsErrorProcessing(1)->save();
    }

    public static function fraudCancelled(Mage_Sales_Model_Order $order)
    {
        if($order->canCancel()) {
            $order->cancel();
        }

        if($order->canHold()) {
            $order->hold();
        }
        self::setState($order,'fraud');
        $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Order canceled due to fraud"));
        $order->save();
    }

    public static function fraudPending(Mage_Sales_Model_Order $order)
    {
        self::setState($order,'fraud');
        $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Extended fraud check being processed"));
        $order->save();
    }

    public static function merchantPending(Mage_Sales_Model_Order $order, Upg_Payments_Model_Message $message)
    {
        $transaction = Mage::getModel('upg_payments/transaction')->getCollection()
            ->addFieldToFilter('order_ref', $order->getIncrementId())
            ->getFirstItem();

        $autoCapture = ($transaction->getAutocapture() > 0?true:false);
        $paymentMethod = $transaction->getPaymentMethod();
        $amount = $message->getAmount();
        $orderAmount = Mage::helper('upg_payments/transaction')->getPriceInLowestUnit($order->getGrandTotal());
        $status = Mage_Sales_Model_Order::STATE_PROCESSING;
        //Check if a full or partial payment was made in case of cash in advance. Add comment accordingly.
        if($paymentMethod == 'PREPAID'){
            if($amount > 0) {
                $convertedAmount = Mage::helper('core')->currencyByStore($amount/100, $order->getStoreId(), true, false);                
                if($amount >= $orderAmount) {
                    //full payment was received
                    $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Prepaid full payment %s", $convertedAmount.' '.$order->getOrderCurrencyCode()));
                }else{
                    //partial payment was received
                    $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Prepaid partial payment %s",$convertedAmount.' '.$order->getOrderCurrencyCode()));
                }
            }
        }
        //Check if the transaction had autocapture enabled
        if(!$autoCapture) {
            //If order is not invoiced yet, add comment
            if($order->hasInvoices() == false)
            {
                $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Waiting for capture"));
            }
        }else{
            //If order is not invoiced yet, create invoice and add comment
            if($order->hasInvoices() == false)
            {
                //Invoice the full order if autocapture was enabled
                Mage::unregister('payco_autocaptue_invoice');
                Mage::register('payco_autocaptue_invoice', $order->getIncrementId());
                if(!self::invoice($order)){
                    $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Order invoice could not be automatically created."));
                }else{
                    $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Order invoice was automatically created."));
                }
                $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Order was captured automatically."));
            }            
        }
        self::setState($order,$status,$status);
        $order->save();
    }

    public static function ciaPending(Mage_Sales_Model_Order $order)
    {
        self::setState($order,'pending_payment','pending_payment');
        $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Cash in advance is pending"));
        $order->save();
    }

    public static function paid(Mage_Sales_Model_Order $order)
    {
        $order->addStatusHistoryComment(Mage::helper('upg_payments')->__("Got paid notification"));
        $order->save();
    }

    public static function paymentFailed(Mage_Sales_Model_Order $order)
    {
        $comment = Mage::helper('upg_payments')->__("The payment failed");
        $order->setData('state', 'payment_review');
        $order->setStatus('payment_review');
        $order->addStatusHistoryComment($comment);
        $order->save();
    }

    public static function chargeBack(Mage_Sales_Model_Order $order)
    {
        $comment = Mage::helper('upg_payments')->__("Charge back was done on this order");
        $order->addStatusHistoryComment($comment);
        $order->save();
    }

    //cleared
    public static function cleared(Mage_Sales_Model_Order $order)
    {
        $comment = Mage::helper('upg_payments')->__("Payment was cleared");
        $order->addStatusHistoryComment($comment);
        $order->save();
    }

    public static function inDunning(Mage_Sales_Model_Order $order)
    {
        $order->hold();
        $comment = Mage::helper('upg_payments')->__("Payment is in dunning");
        $order->addStatusHistoryComment($comment);
        $order->save();
    }

    public static function cancelled(Mage_Sales_Model_Order $order)
    {
        if($order->getState() == 'payment_review')
        {
            $status = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
            self::setState($order, $status, $status);
        }
        if ($order->canCancel()) {
            $order->cancel();
            $order->save();
        }
    }

    private static function invoice(Mage_Sales_Model_Order $order)
    {
        if (!$order->canInvoice()) {
            Mage::helper('upg_payments')->log("Order could not be invoiced ".$order->getIncrementId());
            return false;
        }

        if ($order->hasInvoices()) {
            Mage::helper('upg_payments')->log("Order has been invoiced ".$order->getIncrementId());
            return false;
        }
        try {

            $invoice = Mage::getModel('sales/service_order', $order)->prepareInvoice();
            $invoice->setRequestedCaptureCase(Mage_Sales_Model_Order_Invoice::CAPTURE_ONLINE);
            $invoice->register();
            $transactionSave = Mage::getModel('core/resource_transaction')
                ->addObject($invoice)
                ->addObject($invoice->getOrder());
            $transactionSave->save();

        } catch (Mage_Core_Exception $e) {
            Mage::helper('upg_payments')->log("Order has failed to be invoiced ".$order->getIncrementId().' '.$e->getMessage());
            return false;
        }

        return true;
    }

    private static function setState(Mage_Sales_Model_Order $order, $status=null, $state=null)
    {
        if(!empty($status)){
            $order->setStatus($status);
        }

        if(!empty($state)){
            $order->setData('state', $state);
        }

        $order->save();
    }
}