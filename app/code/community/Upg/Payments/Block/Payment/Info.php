<?php

class Upg_Payments_Block_Payment_Info extends Mage_Payment_Block_Info
{
    private $statusResponse;

    private $publicFields = array(
        'bankname',
        'bic',
        'iban',
        'bankAccountHolder',
        'paymentReference',
        'sepaMandate',
        'email',
        'customerEmail',
    );

    protected function _construct()
    {
        parent::_construct();

        $controllerName = $this->getRequest()->getControllerName();
        $actionName = $this->getRequest()->getActionName();

        if(strstr($controllerName,'onepage') !== false && strstr($actionName,'saveOrder') === false){
            $this->setTemplate("upg/payment/method/checkout_info.phtml");
        }else {
            $this->setTemplate("upg/payment/method/info.phtml");
        }
    }

    public function getPaymentMethodNameCheckOut()
    {
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $orderRef = $quote->getReservedOrderId();
        $transaction = Mage::getModel('upg_payments/transaction')->getCollection()
            ->addFieldToFilter('order_ref', $orderRef)
            ->getFirstItem();

        return $transaction->getPaymentMethod();
    }

    /**
     * @return \Upg\Library\Response\SuccessResponse
     */
    public function getStatusResponse()
    {
        if(empty($this->statusResponse)) {
            $order = Mage::getModel('sales/order')->load($this->getInfo()->getParentId());
            $this->statusResponse = Mage::helper('upg_payments/info')->getTransactionInfoFromOrder($order);
        }
        return $this->statusResponse;
    }

    public function fieldShouldBeShown($fieldName)
    {
        if($this->getIsSecureMode())
        {
            return in_array($fieldName, $this->publicFields);
        }

        return true;
    }

    public function toPdf()
    {
        $this->setTemplate("upg/payment/method/pdf_info.phtml");
        return $this->toHtml();
    }
}