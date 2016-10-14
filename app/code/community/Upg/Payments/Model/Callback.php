<?php

use Upg\Library\Callback\ProcessorInterface;

/**
 * Handles the reserve calls
 * Class Upg_Payments_Model_Reserve
 */
class Upg_Payments_Model_Callback extends Upg_Payments_Model_Abstract implements ProcessorInterface
{
    const NOTIFICATION_TYPE_STATUS = 'PAYMENT_STATUS';
    const NOTIFICATION_TYPE_SELECTION = 'PAYMENT_INSTRUMENT_SELECTION';

    private $notificationType;
    private $message;
    private $merchantID;
    private $storeID;
    private $orderID;
    private $paymentMethod;
    private $resultCode;
    private $merchantReference;
    private $paymentInstrumentID;
    private $additionalInformation;
    private $paymentInstrumentsPageUrl;

    /**
     * @var \Upg\Library\Config
     */
    private $config;

    /**
     * @var Upg_Payments_Helper_Data
     */
    private $helper;

    public function _construct()
    {
        parent::_construct();
        $this->helper = Mage::helper('upg_payments');
        $this->config = $this->helper->getConfig();
    }

    /**
     * Send data to the processor that will be used in the run method
     * Unless specified most parameters will not be blank
     *
     * @param $notificationType - This is the notification type which can be PAYMENT_STATUS,
     *     PAYMENT_INSTRUMENT_SELECTION
     * @param $merchantID - This is the merchantID assigned by PayCo.
     * @param $storeID - This is the store ID of a merchant assigned by PayCo as a merchant can have more than one
     *     store.
     * @param $orderID - This is the order number of the shop.
     * @param $paymentMethod - This is the selected payment method
     * @param $resultCode 0 means OK, any other code means error
     * @param $merchantReference \Upg\Library\Callback\Reference that was set by the merchant during the
     *     createTransaction call. Optional
     * @param $paymentInstrumentID - This is the payment instrument Id that was used
     * @param $paymentInstrumentsPageUrl This is the payment instruments page url.
     * @param array $additionalInformation Optional additional info in an associative array
     * @param $message \Upg\Library\Callback\Details about an error, otherwise not present. Optional
     */
    public function sendData(
        $notificationType,
        $merchantID,
        $storeID,
        $orderID,
        $paymentMethod,
        $resultCode,
        $merchantReference,
        $paymentInstrumentID,
        $paymentInstrumentsPageUrl,
        array $additionalInformation,
        $message
    )
    {
        $this->notificationType = $notificationType;
        $this->merchantID = $merchantID;
        $this->storeID = $storeID;
        $this->orderID = $orderID;
        $this->paymentMethod = $paymentMethod;
        $this->resultCode = $resultCode;
        $this->merchantReference = $merchantReference;
        $this->paymentInstrumentID = $paymentInstrumentID;
        $this->additionalInformation = $additionalInformation;
        $this->message = $message;
        $this->paymentInstrumentsPageUrl = $paymentInstrumentsPageUrl;
    }

    /**
     * The run method.
     * This should return the appropriate url as shown in the manual under the Callback on the link provided for this
     * method.
     * The implementation must return either a sucessful url or an error url to payco.
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/reserve
     * @return string The url as a raw string
     */
    public function run()
    {
        if ($this->resultCode != 0)
        {
            Mage::log('Error for ' . $this->notificationType . ' ' . $this->orderID . ': ' . $this->resultCode . ' ' . $this->message);
        }

        switch ($this->notificationType)
        {
            case self::NOTIFICATION_TYPE_SELECTION:
                return $this->instrumentSelect();
                break;
            case self::NOTIFICATION_TYPE_STATUS:
                return $this->paymentStatus();
                break;
            default:
                break;
        }
    }

    // Gets called to handle 'offsite' payment methods like PayPal.
    // Gets called after the user returns to the site.
    // This provides similar functionality to Payment->authorize() but for PayPal rather that CC for example
    public function paymentStatus()
    {
        $transaction = Mage::getModel('upg_payments/transaction')->getCollection()
            ->addFieldToFilter('order_ref', $this->orderID)
            ->getFirstItem();
        $transactionIdArg = urlencode(base64_encode(Mage::helper('core')->encrypt($transaction->getId())));

        if(!empty($this->paymentInstrumentsPageUrl)) {
            //handle the payment recovery
            $transaction->setRecoveryUrl($this->paymentInstrumentsPageUrl)->save();

            //ok now return the callback page
            return Mage::getUrl('paymentmodule/payment/recovery', array('_secure'=>TRUE,'transaction'=>$transactionIdArg));
        }


        if($this->resultCode == 0) {
            $order = Mage::getModel('sales/order')->loadByIncrementId($this->orderID);
            $order->getPayment()->setIsTransactionPending(false)->setIsTransactionApproved(true)->save();
            $order->getPayment()->addTransaction(Mage_Sales_Model_Order_Payment_Transaction::TYPE_ORDER, null, false, "Adding order transaction");

            $authTransaction = $order->getPayment()->getAuthorizationTransaction();
            $authTransaction->setIsClosed(0)->save();

            $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, Mage_Sales_Model_Order::STATE_PROCESSING, "Got return");
            $order->save();
            //ok for bill and dd create a fake mns message if autocapture is enabled
            Mage::getModel('upg_payments/payment')->createPaidMnsMessage($order, 0);
            $orderArg = urlencode(base64_encode(Mage::helper('core')->encrypt($order->getId())));
            return Mage::getUrl('paymentmodule/payment/complete', array('_secure'=>TRUE,'order'=>$orderArg));
        }
    }

    public function instrumentSelect()
    {
        $newTransaction = false;
        $returnParams = array('_secure'=>TRUE);
        $transaction = Mage::getModel('upg_payments/transaction')->getCollection()
            ->addFieldToFilter('order_ref', $this->orderID)
            ->getFirstItem();
        if($transaction->getId()) {  // We've been here before and stored the transaction data
            $recoveryUrl = $transaction->getRecoveryUrl();
            if(!empty($recoveryUrl)){  // Then we have done at least one recovery
                //ok set an id so the reserve call will be made
                // This lets us know that the callback has been called on a recovery
                $returnParams['transaction'] = urlencode(base64_encode(Mage::helper('core')->encrypt($transaction->getId())));
            }
            
            // Clear out the old data
            $transaction->setData('payment_method', $this->paymentMethod)
                ->setData('payment_instrument_id', $this->paymentInstrumentID)
                ->setData('redirect_url', '')
                ->setData('recovery_url', '');

            if(count($this->additionalInformation) > 0){
                $transaction->setData('additional_information', json_encode($this->additionalInformation));
            }

            $transaction->save();
        } else {  // Here for the first time
            $transaction = Mage::getModel('upg_payments/transaction')
                ->setData('order_ref', $this->orderID)
                ->setData('payment_method', $this->paymentMethod)
                ->setData('payment_instrument_id', $this->paymentInstrumentID);

            if(count($this->additionalInformation) > 0){
                $transaction->setData('additional_information', json_encode($this->additionalInformation));
            }

            $transaction->save();
        }

        //ok now return the callback page
        return Mage::getUrl('paymentmodule/payment/confirm', $returnParams);

    }
}