<?php require_once Mage::getModuleDir('', 'Upg_Payments') . '/Api/vendor/autoload.php';

class Upg_Payments_PaymentController extends Mage_Core_Controller_Front_Action
{
    /**
     * @var Upg_Payments_Helper_Transaction
     */
    protected $transactionHelper;

    /**
     * @var Upg_Payments_Helper_Quote
     */
    protected $quoteHelper;

    /**
     * @var Upg_Payments_Helper_Data
     */
    protected $helper;

    /**
     * Used to fix issues with sessions being messed with in the constructor
     * @return Mage_Core_Controller_Front_Action
     */
    public function preDispatch()
    {
        parent::preDispatch();

        $this->transactionHelper = Mage::helper('upg_payments/transaction');
        $this->quoteHelper = Mage::helper('upg_payments/quote');
        $this->helper = Mage::helper('upg_payments');

        return $this;
    }

    public function indexAction()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $reservedOrderId = $this->quoteHelper->reserveOrderId($quote);
        $transaction = $this->transactionHelper->createTransaction($quote, $reservedOrderId);

        $api = new \Upg\Library\Api\CreateTransaction($this->helper->getConfig($quote->getStoreId(), $quote->getQuoteCurrencyCode()), $transaction);

        try
        {
            $response = $api->sendRequest();
            $result = array(
                'iframeUrl' => $response->getData('redirectUrl')
            );

        } catch (\Upg\Library\Api\Exception\Validation $e) {
            Mage::logException($e);
            Mage::log(print_r($e->getVailidationResults(), true), null, 'upg_magento.log', true);
            $result = array(
                'error' => 1,
                'errorMsg' => $this->__('There was an error please contact the merchant')
            );
        } catch (Exception $e)
        {

            Mage::logException($e);
            $result = array(
                'error' => 1,
                'errorMsg' => $this->__('There was an error please contact the merchant')
            );
        }

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Content-type', 'application/json', true)
            ->setBody(json_encode($result));
    }

    /**
     * Got confirmation from the iframe send js to move on to next step
     */
    public function confirmAction()
    {


        $this->loadLayout();
        $this->renderLayout();

        return;

        $redirectUrl = '';
        $transactionIdEncrypted = $this->getRequest()->getParam('transaction');
        if(!empty($transactionIdEncrypted)) {
            $transactionId = Mage::helper('core')->decrypt(base64_decode($transactionIdEncrypted));
            //make the reserve call as we are recovering this transaction
            $transaction = Mage::getModel('upg_payments/transaction')->load($transactionId);
            if($transaction->getId()) {
                $order = Mage::getModel('sales/order')->loadByIncrementId($transaction->getOrderRef());
                $request = Mage::helper('upg_payments/transaction')->reserveTransaction($order, $transaction);
                Mage::helper('upg_payments/transaction')->sendReserveTransaction($request, $transaction, $order);
                $redirectUrl = $transaction->getRedirectUrl();
                if(empty($redirectUrl)) {
                   //ok we got no redirect url back
                    $orderArg = urlencode(base64_encode(Mage::helper('core')->encrypt($order->getId())));
                    $redirectUrl = Mage::getUrl('paymentmodule/payment/complete', array('_secure'=>TRUE,'order'=>$orderArg));
                }
            }
        }

        if($redirectUrl) {
            Mage::register('payco_recovery_redirect', $redirectUrl);
        }

        $this->loadLayout();
        $this->renderLayout();

    }

    public function recoveryAction()
    {
        $transactionIdEncrypted = $this->getRequest()->getParam('transaction');
        $transactionId = Mage::helper('core')->decrypt(base64_decode($transactionIdEncrypted));

        $transaction = Mage::getModel('upg_payments/transaction')->load($transactionId);

        Mage::register('paycoiframe', $transaction->getRecoveryUrl());
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Redirect to order success page for redirected and recovered orders
     */
    public function completeAction()
    {
        $orderIdEncrypted = $this->getRequest()->getParam('order');
        $orderId = Mage::helper('core')->decrypt(base64_decode($orderIdEncrypted));

        $order = Mage::getModel('sales/order')->load($orderId);

        $order->sendNewOrderEmail();

        Mage::getSingleton('checkout/session')->setLastSuccessQuoteId($order->getQuoteId())
            ->setLastOrderId($order->getId())
            ->setLastRealOrderId($order->getIncrementId());

        $this->getResponse()->setRedirect(Mage::getUrl('checkout/onepage/success', array('_secure'=>TRUE)));
    }

}