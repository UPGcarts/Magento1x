<?php

use Psr\Log\LogLevel;

class Upg_Payments_Helper_Config extends Mage_Core_Helper_Abstract
{
    const CONFIG_PATH = 'payment/upg/';

    const BASE_LOG_PATH = '/var/log/';
    const DEFAULT_LOG = 'upg.log';
    const DEFAULT_API_LOG = 'upg_api.log';

    private $urlLive;
    private $urlTest;

    /**
     * @var Upg_Payments_Helper_Locale
     */
    private $localeHelper;

    public function __construct()
    {
        //Load service urls for live and test
        /** @var Mage_Core_Model_Config_Element urlLive */
        $this->urlLive = Mage::getConfig()->getNode('default/payment/upg/service_urls/live')->asArray();
        $this->urlTest = Mage::getConfig()->getNode('default/payment/upg/service_urls/test')->asArray();
        $this->localeHelper = Mage::helper('upg_payments/locale');
    }

    protected function getConfigValue($key, $storeId = null)
    {
        return trim(Mage::getStoreConfig(self::CONFIG_PATH . $key));
    }

    public function isEnabled()
    {
        return ($this->getConfigValue('enabled') === 1) ? true : false;
    }

    public function getMerchantId($storeId = null)
    {
        return $this->getConfigValue('merchant_id', $storeId);
    }

    public function getMerchantKey($storeId = null)
    {
        $merchantKey = $this->getConfigValue('merchant_key', $storeId);
        return Mage::helper('core')->decrypt($merchantKey);
    }

    public function getEnvironment($storeId = null)
    {
        return $this->getConfigValue('transaction_mode', $storeId);
    }

    public function getProductRiskClass($storeId = null)
    {
        return $this->getConfigValue('product_risk_class', $storeId);
    }

    public function getEnvironmentUrl($storeId = null)
    {
        //Only return live for the exact value, test mode otherwise
        $env = $this->getEnvironment($storeId);
        if ($env === Upg_Payments_Model_SourceModels_Environment::LIVE)
        {
            return $this->urlLive;
        }
        return $this->urlTest;
    }

    public function getRiskClass($storeId = null)
    {
        $value = $this->getConfigValue('risk_class', $storeId);
        return empty($value)?Upg\Library\Risk\RiskClass::RISK_CLASS_DEFAULT:intval($value);
    }

    public function getCheckoutDescription($storeId = null)
    {
        return $this->getConfigValue('checkout_description', $storeId);
    }

    /**
     * Check if auto capture is enabled
     */
    public function getAutoCapture($storeId = null)
    {
        $config = $this->getConfigValue('capture_auto', $storeId);
        return ($config==1 ? true : false);
    }

    public function getB2bEnabled($storeId = null)
    {
        $config = $this->getConfigValue('enable_b2b', $storeId);
        return ($config==1 ? true : false);
    }

    public function getShowExtendedB2bOnCheckout($storeId = null)
    {
        $config = $this->getConfigValue('show_extended_b2b_checkout', $storeId);
        return ($config==1 ? true : false);
    }

    public function getB2bShowRegistrationType($storeId = null)
    {
        $config = $this->getConfigValue('b2b_show_registration_type', $storeId);
        return ($config==1 ? true : false);
    }

    public function getLocale()
    {
        return $this->localeHelper->getLocaleCode();
    }

    /**
     * Get the currency store id for the current currency
     * @return string
     */
    public function getCurrentStoreId($storeId = null, $currency = null)
    {
        $mappings = unserialize($this->getConfigValue('currency_mapping', $storeId));
        if(is_null($currency)) {
            $currency = Mage::app()->getStore()->getCurrentCurrencyCode();
        }
        if(!is_array($mappings))
        {
            return null;
        }
        foreach($mappings as $mapping)
        {
            //Get the current currency
            if($mapping['currency_code'] == $currency)
                return $mapping['store_id'];
        }
        //throw new Exception('Could not find the store ID for currency '.$currency.'. Check store ID mappings');
    }

    public function getRiskClassCustomerGroupMapping($customerGroupId, $storeId = null)
    {
        $mappings = unserialize($this->getConfigValue('customergroup_riskclass', $storeId));
        if(!is_array($mappings) && empty($mappings)) {
            return $this->getRiskClass($storeId);
        }

        foreach($mappings as $mapping) {
            if($mapping['customer_group'] == $customerGroupId) {
                return intval($mapping['customer_riskclass']);
            }
        }

        return $this->getRiskClass($storeId);
    }

    public function canLog($storeId = null)
    {
        return ($this->getConfigValue('log_on', $storeId) == 1) ? true : false;
    }

    public function getLogLocation($storeId = null)
    {
        $target = trim($this->getConfigValue('log_target', $storeId));
        return Mage::getBaseDir().self::BASE_LOG_PATH.((!empty($target)) ? $target : self::DEFAULT_LOG);
    }

    public function getApiLogTarget($storeId = null)
    {
        $target = trim($this->getConfigValue('log_api_target', $storeId));
        return Mage::getBaseDir().self::BASE_LOG_PATH.((!empty($target)) ? $target : self::DEFAULT_API_LOG);
    }

    public function getLogLevel($storeId = null)
    {
        $target = $this->getConfigValue('log_level', $storeId);
        return (!empty($target)) ? $target : LogLevel::ERROR;
    }

    public function getPaymentTarget($storeId = null)
    {
        $paymentTarget = $this->getConfigValue('payment_target', $storeId);
        return empty($paymentTarget) ? 0 : $paymentTarget;
    }

    public function getUpdateTransactionEnabled($storeId = null)
    {
        return $this->getConfigValue('update_transaction_enabled', $storeId);
    }
}