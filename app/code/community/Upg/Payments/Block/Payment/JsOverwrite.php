<?php

class Upg_Payments_Block_Payment_JsOverwrite
    extends Mage_Core_Block_Template
{
    const CONFIG_PATH = 'payment/upg/';
    const CONFIG_NAMESPACE = 'payment_iframe_dimensions';
    
    protected function getConfigValue($key)
    {
        return trim(Mage::getStoreConfig(self::CONFIG_PATH . self::CONFIG_NAMESPACE . $key));
    }
    
    public function getUnits()
    {
        return $this->getConfigValue('_units');
    }
    
    public function getWidth()
    {
        return $this->getConfigValue('_width');
    }
    
    public function getHeight()
    {
        return $this->getConfigValue('_height');
    }
    
    public function getFitToWidthDesktop()
    {
        return $this->getConfigValue('_fit_to_width_desktop') == 1
            ? true : false;
    }
    
    public function getMaxMobileBrowserWidth()
    {
        return $this->getConfigValue('_max_mobile_browser_width');
    }
}