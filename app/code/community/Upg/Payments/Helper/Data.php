<?php require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

use Upg\Library\Config;

class Upg_Payments_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Upg_Payments_Helper_Config
     */
    protected $configHelper;

    public function __construct($storeId = null, $currency = null)
    {
        $this->configHelper = Mage::helper('upg_payments/config');
        $this->config = new Config($this->getApiConfigArray($storeId, $currency));
    }

    protected function getApiConfigArray($storeId = null, $currency = null)
    {
        return array(
            'merchantID' => $this->configHelper->getMerchantId($storeId),
            'merchantPassword' => $this->configHelper->getMerchantKey($storeId),
            'storeID' => $this->configHelper->getCurrentStoreId($storeId, $currency),
            'baseUrl' => $this->configHelper->getEnvironmentUrl($storeId),
            'defaultRiskClass' => $this->configHelper->getRiskClass($storeId),
            'defaultLocale' => $this->configHelper->getLocale($storeId),
            'logEnabled' => $this->configHelper->canLog($storeId),
            'logLevel' => $this->configHelper->getLogLevel($storeId),
            'logLocationMain' => $this->configHelper->getLogLocation($storeId),
            'logLocationRequest' => $this->configHelper->getApiLogTarget($storeId)
        );
    }

    public function getB2bEnabled($storeId = null)
    {
        return $this->configHelper->getB2bEnabled($storeId);
    }

    public function getB2bShowRegistrationType($storeId = null)
    {
        return $this->configHelper->getB2bShowRegistrationType($storeId);
    }

    public function getShowExtendedB2bOnCheckout($storeId = null)
    {
        return $this->configHelper->getShowExtendedB2bOnCheckout($storeId);
    }

    public function getRegistrationTypeOptions()
    {
        return Mage::getModel('upg_payments/sourceModels_registertype')->toArray();
    }

    /**
     * @return Config
     */
    public function getConfig($storeId = null, $currency = null)
    {
        if(!is_null($storeId) && !is_null($currency)) {
            $this->config = new Config($this->getApiConfigArray($storeId, $currency));
        }

        return $this->config;
    }

    /**
     * Logs Magento specific messages for Payco Payments
     * @param $message
     */
    public function log($message)
    {
        Mage::log($message, null, 'upg_magento.log', true);
    }
}