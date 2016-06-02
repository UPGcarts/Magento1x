<?php

use Upg\Library\Locale\Codes as LC;

class Upg_Payments_Helper_Locale extends Mage_Core_Helper_Abstract
{

    const DEFAULT_LOCALE = LC::LOCALE_EN;

    /**
     * Array of Magento locales to map to Payco Locales
     * @var array
     */
    private $localeMap;

    public function __construct()
    {
        $this->localeMap = array(
            LC::LOCALE_EN => array('en_GB', 'en_US', 'en_AU', 'en_CA', 'en_NZ', 'en_IE'),
            LC::LOCALE_DE => 'de_DE',
            LC::LOCALE_ES => 'es_ES',
            LC::LOCALE_FI => 'fi_FI',
            LC::LOCALE_FR => 'fr_FR',
            LC::LOCALE_IT => 'it_IT',
            LC::LOCALE_NL => 'nl_NL',
            LC::LOCALE_RU => 'ru_RU',
            LC::LOCALE_TU => 'tr_TR'
        );
    }

    /**
     *
     * @param $code
     * @return string
     * @throws Exception
     */
    public function checkLocale($code)
    {
        foreach($this->localeMap as $paycoLocale => $mageLocale)
        {
            if(is_array($mageLocale))
            {
                //Multiple locales for Payco locale
                if(in_array($code, $mageLocale))
                {
                    return $paycoLocale;
                }
                continue;
            }

            if($mageLocale == $code)
                return $paycoLocale;
        }

        throw new Exception('Could not find locale "'.$code.'"');
    }

    /**
     * @return string
     */
    public function getLocaleCode()
    {
        $mageLocale = Mage::app()->getLocale()->getLocaleCode();

        try
        {
            $locale = $this->checkLocale($mageLocale);
        } catch (Exception $e)
        {
            //Return default locale
            Mage::helper('upg_payments')->log($e->getMessage());
            Mage::helper('upg_payments')->log('Using default '.self::DEFAULT_LOCALE.' instead');
            $locale = self::DEFAULT_LOCALE;
        }

        return $locale;
    }
}