<?php require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

/**
 * Mns message in db
 * Class Upg_Payments_Model_Transaction
 */
class Upg_Payments_Model_Message extends Mage_Core_Model_Abstract
    implements \Upg\Library\Mns\ProcessorInterface
{
    protected function _construct()
    {
        $this->_init('upg_payments/message');
        parent::_construct();
    }

    /**
     * @param $merchantID This is the merchantID assigned by PayCo.
     * @param $storeID This is the store ID of a merchant assigned by PayCo as a merchant can have more than one store.
     * @param $orderID This is the order number tyhat the shop has assigned
     * @param $captureID The confirmation ID of the capture. Only sent for Notifications that belong to captures
     * @param $merchantReference Reference that can be set by the merchant during the createTransaction call.
     * @param $paymentReference The reference number of the
     * @param $userID The unique user id of the customer.
     * @param $amount This is either the amount of an incoming payment or “0” in case of some status changes
     * @param $currency  Currency code according to ISO4217.
     * @param $transactionStatus Current status of the transaction. Same values as resultCode
     * @param $orderStatus Possible values: PAID PAYPENDING PAYMENTFAILED CHARGEBACK CLEARED. Status of order
     * @param $additionalData Json string with aditional data
     * @param $timestamp Unix timestamp, Notification timestamp
     * @param $version notification version (currently 1.5)
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/notification-call
     */
    public function sendData(
        $merchantID,
        $storeID,
        $orderID,
        $captureID,
        $merchantReference,
        $paymentReference,
        $userID,
        $amount,
        $currency,
        $transactionStatus,
        $orderStatus,
        $additionalData,
        $timestamp,
        $version
    ){
        $this->setData(
            array(
                'merchant_id' => $merchantID,
                'store_id' => $storeID,
                'order_id' => $orderID,
                'capture_id' => $captureID,
                'merchant_reference' => $merchantReference,
                'payment_reference' => $paymentReference,
                'user_id' => $userID,
                'amount' => $amount,
                'currency' => $currency,
                'transaction_status' => $transactionStatus,
                'order_status' => $orderStatus,
                'additional_data' => $additionalData,
                'version' => $version,
                'mns_timestamp' => $timestamp,
            )
        );
    }

    /**
     * The run method used by the processor to run successfuly validated MNS notifications.
     * This should not return anything
     */
    public function run()
    {
        $this->save();
    }
}