<?php
require_once Mage::getModuleDir('', 'Upg_Payments') . '/Api/vendor/autoload.php';

use Upg\Library\Request\GetTransactionStatus as GetTransactionStatusRequest;
use Upg\Library\Api\GetTransactionStatus;

class Upg_Payments_Helper_Info extends Mage_Core_Helper_Abstract
{

    /**
     * @var Config
     */
    protected $config;

    public function __construct()
    {
        $this->config = Mage::helper('upg_payments')->getConfig();
    }

    public function getTransactionInfoFromPayment(Mage_Payment_Model_Info $payment)
    {
        $orderId = $this->getInfo()->getParentId();
        $order = Mage::getModel('sales/order')->load($orderId);

        return $this->getTransactionInfoFromOrder($order);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return bool|\Upg\Library\Response\SuccessResponse
     */
    public function getTransactionInfoFromOrder(Mage_Sales_Model_Order $order)
    {
        $request = new GetTransactionStatusRequest($this->config);
        $request->setOrderID($order->getIncrementId());

        try{
            $api = new GetTransactionStatus($this->config, $request);
            $response = $api->sendRequest();

            return $response;

        }catch (Exception $e) {
            Mage::log("Lookup failed for {$order->getIncrementId()} - {$e->getMessage()}", null, 'upg_status_lookup', true);
            return false;
        }
    }
}