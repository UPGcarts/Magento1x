<?php
use Upg\Library\Config;
use Upg\Library\Request\CreateTransaction;
use Upg\Library\Request\Reserve;
use Upg\Library\Request\Capture;
use Upg\Library\Request\Refund;
use Upg\Library\Request\Finish;
use Upg\Library\Request\Cancel;
use Upg\Library\Api\Reserve as ReserveCall;
use Upg\Library\Api\Capture as CaptureCall;
use Upg\Library\Api\Refund as RefundCall;
use Upg\Library\Api\Finish as FinishCall;
use Upg\Library\Api\Cancel as CancelCall;
use Upg\Library\Request\Objects\Address;
use Upg\Library\Request\Objects\Amount;
use Upg\Library\Request\Objects\BasketItem;
use Upg\Library\Request\Objects\Person;
use Upg\Library\Request\Objects\Company;
use Upg\Library\Risk\RiskClass;

/**
 * Class Upg_Payments_Helper_Transaction
 * Various methods to help construct a transaction object from a sales quote
 */
class Upg_Payments_Helper_Transaction extends Mage_Core_Helper_Abstract
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Upg_Payments_Helper_Locale
     */
    protected $localeHelper;

    /**
     * @var Upg_Payments_Helper_Config
     */
    protected $configHelper;

    public function __construct()
    {
        $this->config = Mage::helper('upg_payments')->getConfig();
        $this->localeHelper = Mage::helper('upg_payments/locale');
        $this->configHelper = Mage::helper('upg_payments/config');
    }

    public function mapGenderToSalutation($gender)
    {
        switch ($gender)
        {
            case 1:
                return "M";
                break;
            case 2:
                return "F";
                break;
        }

        throw new Exception("Invalid gender: " . $gender);
    }

    /**
     * Get the details about the billing customer
     * @param Mage_Sales_Model_Quote_Address $billing
     * @return Person
     */
    public function getPerson(Mage_Sales_Model_Quote $quote)
    {
        $billing = $quote->getBillingAddress();
        $email = $billing->getEmail();
        if(empty($email))
        {
            $email = $quote->getCustomerEmail();
        }
        $user = new Person();
        $user->setSalutation($this->mapGenderToSalutation($quote->getCustomerGender()))
            ->setName($billing->getFirstname())
            ->setSurname($billing->getLastname())
            ->setEmail($email);
        //->setPhoneNumber($billing->getTelephone());
        //->setFaxNumber($billing->getFax());

        return $user;
    }

    /**
     * Get the customer billing address
     * @param Mage_Sales_Model_Quote_Address $billingAddress
     * @return Address
     */
    public function getQuoteAddress(Mage_Sales_Model_Quote_Address $billingAddress)
    {

        $address = new Address();
        $address->setStreet($billingAddress->getStreetFull())
            ->setZip($billingAddress->getPostcode())
            ->setCity($billingAddress->getCity())
            ->setState($billingAddress->getRegion())
            ->setCountry($billingAddress->getCountry());

        return $address;
    }

    public static function getPriceInLowestUnit($price)
    {
        $floatfix = 0.000000001;
        return (int)floor($price * 100 + $floatfix);
    }

    /**
     * Get the total amount for the transaction
     * @param Mage_Sales_Model_Quote_Item $item
     * @return Amount
     */
    public function getQuoteItemAmount(Mage_Sales_Model_Quote_Item $item)
    {
        return new Amount($this->getPriceInLowestUnit($item->getRowTotal()));
    }

    /**
     * Get the total amount for the transaction
     * @param Mage_Sales_Model_Quote_Item $item
     * @return Amount
     */
    public function getOrderItemAmount(Mage_Sales_Model_Order_Item $item)
    {
        return new Amount($this->getPriceInLowestUnit($item->getRowTotal()));
    }

    /**
     * Get basket item objects and set the basket item on a transaction as well as total amount
     * @param Mage_Sales_Model_Quote_Item[] $quoteItems
     * @return BasketItem[]
     */
    public function setItems($quoteItems, CreateTransaction $request = null)
    {
        $resource = Mage::getModel('catalog/product')->getResource();

        $result = array();

        $price = 0;
        $tax = 0;
        $percent = 0;

        foreach ($quoteItems as $item)
        {
            $t = new BasketItem();
            $amount = $this->getQuoteItemAmount($item);
            $t->setBasketItemAmount($amount)
                ->setBasketItemCount($item->getQty())
                ->setBasketItemText($item->getName())
                ->setBasketItemID($item->getId())
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_DEFAULT);

            $price += $amount->getAmount();
            $tax += $amount->getVatAmount();
            $percent = $amount->getVatRate();

            if ($request != null)
            {
                $riskClass = intval($resource->getAttributeRawValue($item->getProductId(), 'upg_risk_class', $item->getStoreId()));

                if($request->getUserRiskClass() != $riskClass && $this->configHelper->getProductRiskClass()) {
                    $t->setBasketItemRiskClass($riskClass);
                }

                $request->addBasketItem($t);

            }

            $result[] = $t;
        }

        return $result;
    }

    /**
     * Get the user (customer) ID or null if not logged in
     * @return null|string
     */
    public function getUserId()
    {
        if (Mage::getSingleton('customer/session')->isLoggedIn())
        {
            return strval(Mage::getSingleton('customer/session')->getCustomer()->getId());
        }
        return null;
    }

    /**
     * Returns an appropriate user id for a guest user
     */
    public function getGuestUserId($orderId)
    {
        return 'GUEST:ORDER:'.$orderId;
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return CreateTransaction
     */
    public function createTransaction(Mage_Sales_Model_Quote $quote, $orderId)
    {
        $request = new CreateTransaction(Mage::helper('upg_payments')->getConfig($quote->getStoreId(), $quote->getQuoteCurrencyCode()));

        //Assign the items to the basket and calculate amounts including the total
        $addedItems = $this->setItems($quote->getAllVisibleItems(), $request);
        if($quote->getShippingAddress()->getShippingAmount() > 0) {
            $shippingItem = new BasketItem();
            $shippingItem->setBasketItemAmount(new Amount($this->getPriceInLowestUnit($quote->getShippingAddress()->getShippingAmount())))
                ->setBasketItemCount(1)
                ->setBasketItemText('shipping')
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_SHIPPINGCOST);

            $request->addBasketItem($shippingItem);

        }
        //now check if there is a discount not covered by the rows grand total
        $this->addQuoteDiscount($addedItems, $quote, $request);

        $person = $this->getPerson($quote);
        $address = $this->getQuoteAddress($quote->getBillingAddress());
        $shippingAddress = $this->getQuoteAddress($quote->getShippingAddress());
        $shippingRecipient = $quote->getShippingAddress()->getFirstname() . ' ' . $quote->getShippingAddress()->getLastname();

        $userId = $this->getUserId();
        if($userId === null)
        {
            $userId = $this->getGuestUserId($orderId);
        }

        $request
            ->setOrderID($orderId)
            ->setIntegrationType(CreateTransaction::INTEGRATION_TYPE_HOSTED_BEFORE)
            ->setAutoCapture($this->configHelper->getAutoCapture())//
            ->setContext(CreateTransaction::CONTEXT_ONLINE)
            ->setUserType(CreateTransaction::USER_TYPE_PRIVATE)
            ->setAmount(new Amount($this->getPriceInLowestUnit($quote->getGrandTotal())))
            ->setUserRiskClass($this->getUserRiskClass())//Pull from config
            ->setUserIpAddress(Mage::helper('core/http')->getRemoteAddr())//Set as current IP
            ->setUserData($person)
            ->setBillingAddress($address)
            ->setShippingAddress($shippingAddress)
            ->setShippingRecipient($shippingRecipient)
            ->setLocale($this->localeHelper->getLocaleCode()); //Get from current language set in config

        $this->addBusinessDetails($quote, $request);
        if($request->getUserType() == CreateTransaction::USER_TYPE_PRIVATE)
        {
            $request->setUserID($userId.'PRIVATE');
        }else
        {
            $request->setUserID($userId.'BUSINESS');
        }

        $transaction = Mage::getModel('upg_payments/transaction')
            ->setData('order_ref', $orderId)
            ->setData('autocapture', $this->configHelper->getAutoCapture());
        $transaction->save();

        return $request;
    }

    public function getUserRiskClass()
    {

        $defaultRiskClass = $this->configHelper->getRiskClass();
        $riskClass = $defaultRiskClass;

        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $groupId = Mage::getSingleton('customer/session')->getCustomer()->getGroupId();
            $riskClass = $this->configHelper->getRiskClassCustomerGroupMapping($groupId);
        }

        return $riskClass;
    }

    private function addQuoteDiscount(array $addedItems, Mage_Sales_Model_Quote $quote, CreateTransaction $request)
    {
        $calculatedGrandTotal = 0;

        foreach($addedItems as $item) {
            /**
             * @var BasketItem $item
             **/
            $calculatedGrandTotal += $item->getBasketItemAmount()->getAmount();
        }

        if($quote->getShippingAddress()->getShippingAmount() > 0) {
            $calculatedGrandTotal += $this->getPriceInLowestUnit($quote->getShippingAddress()->getShippingAmount());
        }

        $difference = $calculatedGrandTotal - $this->getPriceInLowestUnit($quote->getGrandTotal());

        if($difference > 0) {
            $discount = new BasketItem();
            $discount->setBasketItemAmount(new Amount($difference))
                ->setBasketItemCount(1)
                ->setBasketItemText('discount')
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_COUPON);

            $request->addBasketItem($discount);
        }


    }

    private function addBusinessDetails(Mage_Sales_Model_Quote $quote, CreateTransaction $request)
    {
        $billingAddress = $quote->getBillingAddress();
        $companyName = $billingAddress->getCompany();
        $vatId = $billingAddress->getVatId();
        $taxVat = $quote->getCustomerTaxvat();
        $upgCompanyRegistrationId =  trim($billingAddress->getUpgCompanyRegistrationId());
        $upgCompanyRegistrationType = trim($billingAddress->getUpgCompanyRegistrationType());

        if(!empty($companyName) && !empty($upgCompanyRegistrationId) && $this->configHelper->getB2bEnabled()) {
            //$companyName Company
            $company = new Company();
            $company->setCompanyName($companyName)->setCompanyRegistrationID($upgCompanyRegistrationId);


            if (preg_match('/(\d|[a-z]|[A-Z]){1,30}/', $vatId)) {
                $company->setCompanyVatID($vatId);
            }

            if (preg_match('/(\d|[a-z]|[A-Z]){1,30}/', $taxVat)) {
                $company->setCompanyTaxID($taxVat);
            }

            if(!empty($upgCompanyRegistrationType)) {
                $company->setCompanyRegisterType($upgCompanyRegistrationType);
            }

            $request->setCompanyData($company);
            $request->setUserType(CreateTransaction::USER_TYPE_BUSINESS);
        }
    }

    /**
     * @param Reserve $request
     * @param Upg_Payments_Model_Transaction $transaction
     * @param Mage_Sales_Model_Order $order
     * @return \Upg\Library\Response\SuccessResponse
     * @throws Exception
     */
    public function sendReserveTransaction(Reserve $request, Upg_Payments_Model_Transaction $transaction, Mage_Sales_Model_Order $order)
    {
        try{
            $config = Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode());
            $apiReserve = new ReserveCall($config, $request);
            $response = $apiReserve->sendRequest();
            $responseData = $response->getAllData();
            if(array_key_exists('redirectUrl', $responseData)) {
                $transaction->setRedirectUrl($responseData['redirectUrl']);
                $transaction->save();
            }
            return $response;
        } catch (\Upg\Library\Api\Exception\Validation $e) {
            throw new Exception($e->getMessage().' '.print_r($e->getVailidationResults(), true));
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param Upg_Payments_Model_Transaction $transaction
     * @return Reserve
     */
    public function reserveTransaction(Mage_Sales_Model_Order $order, Upg_Payments_Model_Transaction $transaction)
    {
        $paymentInstrumentId = $transaction->getPaymentInstrumentId();

        $total = Mage::getModel('checkout/cart')->getQuote()->getGrandTotal();

        $request = new Reserve(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()));
        $request->setOrderID($order->getIncrementId())
            ->setPaymentMethod($transaction->getPaymentMethod())
            ->setAmount(new Amount($this->getPriceInLowestUnit($order->getGrandTotal())));

        $request = $this->setItemsReserve($order, $request);

        if(!empty($paymentInstrumentId)) {
            $request->setPaymentInstrumentID($paymentInstrumentId);
        }

        return $request;

    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @param $captureId
     * @param $amount
     * @return Capture
     */
    public function getCaptureTransaction(Mage_Sales_Model_Order $order, $captureId, $amount)
    {
        $request = new Capture(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()));
        $request->setOrderID($order->getIncrementId())
            ->setCaptureID($captureId)
            ->setAmount(new Amount($this->getPriceInLowestUnit($amount)));

        return $request;
    }

    /**
     * Send capture transaction
     * @param Capture $request
     * @param Mage_Sales_Model_Order $order
     * @return \Upg\Library\Response\SuccessResponse
     * @throws Exception
     */
    public function sendCaptureTransaction(\Upg\Library\Request\Capture $request, Mage_Sales_Model_Order $order)
    {
        try {
            $api = new CaptureCall(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()), $request);
            $response = $api->sendRequest();
            return $response;
        }Catch(Exception $e){
            Mage::helper('upg_payments')->log("Capture failed for {$request->getOrderID()} - Reason {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Get Finish transaction
     * @param Mage_Sales_Model_Order $order
     * @return Finish
     */
    public function getFinishTransaction(Mage_Sales_Model_Order $order)
    {
        $request = new Finish(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()));
        $request->setOrderID($order->getIncrementId());

        return $request;
    }

    /**
     * Send finish transaction
     * @param Finish $request
     * @param Mage_Sales_Model_Order $order
     * @return \Upg\Library\Response\SuccessResponse
     * @throws Exception
     */
    public function sendFinishTransaction(\Upg\Library\Request\Finish $request, Mage_Sales_Model_Order $order)
    {
        try {
            $api = new FinishCall(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()), $request);
            $response = $api->sendRequest();
            return $response;
        }catch (Exception $e){
            Mage::helper('upg_payments')->log("Finish failed for {$request->getOrderID()} - Reason {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Send the cancel transation
     * @param Mage_Sales_Model_Order $order
     * @return Cancel
     */
    public function getCancelTransaction(Mage_Sales_Model_Order $order)
    {
        $request = new Cancel(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()));
        $request->setOrderID($order->getIncrementId());

        return $request;
    }

    /**
     * Send cancle transaction
     * @param Cancel $request
     * @param Mage_Sales_Model_Order $order
     * @return \Upg\Library\Response\SuccessResponse
     * @throws Exception
     */
    public function sendCancelTransaction(\Upg\Library\Request\Cancel $request, Mage_Sales_Model_Order $order)
    {
        try {
            $api = new CancelCall(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()), $request);
            $response = $api->sendRequest();
            return $response;
        }catch (Exception $e){
            Mage::helper('upg_payments')->log("Cancel failed for {$request->getOrderID()} - Reason {$e->getMessage()}");
            throw $e;
        }
    }


    /**
     * @param Mage_Sales_Model_Order $order
     * @param $captureId
     * @param $amount
     * @return Capture
     */
    public function getRefundTransaction(Mage_Sales_Model_Order $order, $captureId, $amount)
    {
        $request = new Refund(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()));
        $request->setOrderID($order->getIncrementId())
            ->setCaptureID($captureId)
            ->setAmount(new Amount($this->getPriceInLowestUnit($amount)))
            ->setRefundDescription("Refund triggered on Magento for $captureId");

        return $request;
    }

    public function sendRefundTransaction(Refund $refund, Mage_Sales_Model_Order $order)
    {
        try {
            $api = new RefundCall(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()), $refund);
            $response = $api->sendRequest();
            return $response;
        }Catch(Exception $e){
            Mage::helper('upg_payments')->log("Refund failed for {$refund->getOrderID()} - Reason {$e->getMessage()}");
            throw $e;
        }
    }

    private function setItemsReserve(Mage_Sales_Model_Order $order, Reserve $request)
    {
        $calculatedGradTotal = 0;

        $items = $order->getAllVisibleItems();

        foreach($items as $item) {
            $t = new BasketItem();
            $amount = $this->getOrderItemAmount($item);
            $t->setBasketItemAmount($amount)
                ->setBasketItemCount($item->getQty())
                ->setBasketItemText($item->getName())
                ->setBasketItemID($item->getId());

            $request->addBasketItem($t);
            $calculatedGradTotal += $amount->getAmount();
        }

        $shippingAmount = $order->getShippingAmount();
        if($shippingAmount > 0) {
            $shippingItem = new BasketItem();
            $shippingItem->setBasketItemAmount(new Amount($shippingAmount))
                ->setBasketItemCount(1)
                ->setBasketItemText('shipping')
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_SHIPPINGCOST);

            $request->addBasketItem($shippingItem);
            $calculatedGradTotal += $shippingAmount;
        }

        $difference = $calculatedGradTotal - $this->getPriceInLowestUnit($order->getGrandTotal());

        if($difference > 0) {
            $discount = new BasketItem();
            $discount->setBasketItemAmount(new Amount($difference))
                ->setBasketItemCount(1)
                ->setBasketItemText('discount')
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_COUPON);

            $request->addBasketItem($discount);
        }


        return $request;
    }
}