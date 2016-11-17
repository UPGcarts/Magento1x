<?php
use Upg\Library\Config;
use Upg\Library\Request\CreateTransaction;
use Upg\Library\Request\UpdateTransaction;
use Upg\Library\Request\Reserve;
use Upg\Library\Request\Capture;
use Upg\Library\Request\Refund;
use Upg\Library\Request\Finish;
use Upg\Library\Request\Cancel;
use Upg\Library\Api\UpdateTransaction as UpdateTransactionCall;
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
use Upg\Library\PaymentMethods\Methods;
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

    /**
     * @var Upg_Payments_Helper_PaymentFee
     */
    protected $paymentFeeHelper;

    public function __construct()
    {
        $this->config = Mage::helper('upg_payments')->getConfig();
        $this->localeHelper = Mage::helper('upg_payments/locale');
        $this->configHelper = Mage::helper('upg_payments/config');
        $this->paymentFeeHelper = Mage::helper('upg_payments/paymentFee');
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
        $street = $billingAddress->getStreet();
        $address = new Address();
        $address->setStreet(isset($street[0]) ? $street[0] : '')
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
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_DEFAULT);

            $price += $amount->getAmount();
            $tax += $amount->getVatAmount();
            $percent = $amount->getVatRate();

            if ($request != null)
            {
                $riskClass = intval($resource->getAttributeRawValue($item->getProductId(), 'upg_risk_class', $item->getStoreId()));

                if($request->getUserRiskClass() !== $riskClass && $this->configHelper->getProductRiskClass()) {
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
     * @param Mage_Sales_Model_Order $order
     * @param Mage_Sales_Model_Order_Invoice $invoice
     * @return UpdateTransaction $request
     */
    public function getUpdateTransaction(Mage_Sales_Model_Order $order, Mage_Sales_Model_Order_Invoice $invoice, $captureID)
    {
        $request = new UpdateTransaction(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()));

        $request
            ->setOrderID($order->getIncrementId())
            ->setCaptureID($captureID)
            ->setInvoiceNumber($invoice->getIncrementId())
            ->setInvoiceDate(new DateTime($invoice->getCreatedAt()))
            ->setOriginalInvoiceAmount(new \Upg\Library\Request\Objects\Amount($this->getPriceInLowestUnit($invoice->getGrandTotal())));

        // Set due date and payment target if payment method is bill pay
        $transaction = Mage::getModel('upg_payments/transaction')
            ->getCollection()
            ->addFieldToFilter('order_ref', array('eq' => $order->getIncrementId()))
            ->getFirstItem();
        $paymentMethod = $transaction->getPaymentMethod();
        if ($paymentMethod === Methods::PAYMENT_METHOD_TYPE_BILL || $paymentMethod === Methods::PAYMENT_METHOD_TYPE_BILL_SECURE) {
            // Payment Target
            $createdDate = (new DateTime())->setTimestamp($invoice->getCreatedAtDate()->getTimestamp());
            $paymentTargetDays = $this->configHelper->getPaymentTarget();
            $paymentTargetInterval = new DateInterval("P{$paymentTargetDays}D");
            $paymentTarget = $createdDate->add($paymentTargetInterval);  // createdDate + paymentTarget
            $request->setPaymentTarget($paymentTarget);
            // Due Date
            $createdDate = (new DateTime())->setTimestamp($invoice->getCreatedAtDate()->getTimestamp());
            $minDueDate = new DateInterval('P30D');  // Min due date is 30 days (this value is set by German law)
            $dueDate = $createdDate->add($minDueDate);
            $dueDate->add($paymentTargetInterval);  // createdDate + paymentTarget + minDueDate
            $request->setDueDate($dueDate);
        }

        // Add invoice pdf
        $pdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice));
        $pdfWrapper = Mage::getModel('upg_payments/pdfWrapper');
        $pdfWrapper->setPdf($pdf);
        $request->setInvoicePDF($pdfWrapper);

        return $request;
    }

    public function sendUpdateTransaction(Mage_Sales_Model_Order $order, $request)
    {
        try {
            $api = new UpdateTransactionCall(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()), $request);
            $response = $api->sendRequest();
            return $response;
        } catch (Exception $e) {
            Mage::helper('upg_payments')->log("Update Transaction failed for {$request->getOrderID()} - Reason {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * @param Mage_Sales_Model_Quote $quote
     * @return CreateTransaction
     */
    public function createTransaction(Mage_Sales_Model_Quote $quote, $orderId)
    {
        $request = new CreateTransaction(Mage::helper('upg_payments')->getConfig($quote->getStoreId(), $quote->getQuoteCurrencyCode()));

        // Remove any old fees from the quote
        $quote->setData('upg_payments_fee', null);
        $quote->setData('upg_payments_base_fee', null);
        $quote->collectTotals();

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

        // Create hostedPagesText Objects including custom payment fees
        $currencyCode = $quote->getQuoteCurrencyCode();
        $locale = $request->getLocale();
        $paymentFees = $this->configHelper->getCustomPaymentFees($currencyCode, $locale);
        foreach ($paymentFees as &$paymentFee) {
            // Calculate the fee and base_fee for each payment type
            $fee = $this->paymentFeeHelper->calcFee(
                $paymentFee['paymentfee_feetype'],
                $paymentFee['paymentfee_feeamount'],
                $quote->getGrandTotal()
            );

            if ($paymentFee['paymentfee_feetype'] === 'flat') {
                // Need to convert flat fee into base currency
                $currency = Mage::helper('upg_payments/currency');
                $base_fee = $currency->convertToBaseCurrency($paymentFee['paymentfee_feeamount']);
            }
            else {
                $base_fee = $this->paymentFeeHelper->calcFee(
                    $paymentFee['paymentfee_feetype'],
                    $paymentFee['paymentfee_feeamount'],
                    $quote->getBaseGrandTotal()
                );
            }

            // These later get stored in the Transaction DB table
            $paymentFee['paymentfee_fee'] = $this->getPriceInLowestUnit($fee);
            $paymentFee['paymentfee_basefee'] = $this->getPriceInLowestUnit($base_fee);

            // Add hostedPagesText for this payment type
            $this->paymentFeeHelper->addHostedPagesTextToRequest($request, $paymentFee);

            // If payment method is credit card then also need to add hostedPagesText for 3D secure
            if ($paymentFee['paymentfee_paymentmethod'] === Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_CC) {
                $paymentFee3DSecure = array(
                    'paymentfee_currency' => $paymentFee['paymentfee_currency'],
                    'paymentfee_locale' => $paymentFee['paymentfee_locale'],
                    'paymentfee_text' => $paymentFee['paymentfee_text'],
                    'paymentfee_feetype' => $paymentFee['paymentfee_feetype'],
                    'paymentfee_feeamount' => $paymentFee['paymentfee_feeamount'],
                    'paymentfee_fee' => $paymentFee['paymentfee_fee'],
                    'paymentfee_basefee' => $paymentFee['paymentfee_basefee'],
                    'paymentfee_paymentmethod' => Upg\Library\PaymentMethods\Methods::PAYMENT_METHOD_TYPE_CC3D
                );
                $this->paymentFeeHelper->addHostedPagesTextToRequest($request, $paymentFee3DSecure);
                $paymentFees[] = $paymentFee3DSecure;  // Add this to be stored in Transaction DB table
            }
        }

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

        // Add payment fee data to Transaction DB table
        if (count($paymentFees) === 0) $transaction->setData('payment_fees', '');
        else $transaction->setData('payment_fees', serialize($paymentFees));

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
        $additional_information = $transaction->getAdditionalInformation();

        $total = Mage::getModel('checkout/cart')->getQuote()->getGrandTotal();

        $request = new Reserve(Mage::helper('upg_payments')->getConfig($order->getStoreId(), $order->getOrderCurrencyCode()));
        $request->setOrderID($order->getIncrementId())
            ->setPaymentMethod($transaction->getPaymentMethod())
            ->setAmount(new Amount($this->getPriceInLowestUnit($order->getGrandTotal())));

        $request = $this->setItemsReserve($order, $request);

        if(!empty($paymentInstrumentId)) {
            $request->setPaymentInstrumentID($paymentInstrumentId);
        }
        if(!empty($additional_information))        {
            $request->setAdditionalInformation($additional_information);
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
        $helper = Mage::helper('upg_payments');
        $resource = Mage::getModel('catalog/product')->getResource();

        $calculatedGradTotal = 0;

        $items = $order->getAllVisibleItems();
        foreach($items as $item) {
            $t = new BasketItem();
            $amount = $this->getOrderItemAmount($item);
            $t->setBasketItemAmount($amount)
                ->setBasketItemCount($item->getQtyOrdered())
                ->setBasketItemText($item->getName())
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_DEFAULT);

            if ($this->configHelper->getProductRiskClass()) {
                $riskClass = intval($resource->getAttributeRawValue($item->getProductId(), 'upg_risk_class', $item->getStoreId()));
                $t->setBasketItemRiskClass($riskClass);
            }

            $request->addBasketItem($t);
            $calculatedGradTotal += $amount->getAmount();
        }

        // Add payment fee as basket item if fee has been set
        if (! is_null($order->getData('upg_payments_fee'))) {
            $t = new BasketItem();
            $amount = new Amount($this->getPriceInLowestUnit($order->getData('upg_payments_fee')));
            $t->setBasketItemAmount($amount)
                ->setBasketItemCount(1)
                ->setBasketItemText($helper->__('Payment Fee'))
                ->setBasketItemType(\Upg\Library\Basket\BasketItemType::BASKET_ITEM_TYPE_DEFAULT);
            $request->addBasketItem($t);
            $calculatedGradTotal += $amount->getAmount();
        }

        $shippingAmount = $this->getPriceInLowestUnit($order->getShippingAmount());
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