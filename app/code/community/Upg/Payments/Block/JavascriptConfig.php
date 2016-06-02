<?php

class Upg_Payments_Block_JavascriptConfig extends Mage_Core_Block_Template
{
    /**
     * Outputs some things needed by Javascript
     */
    public function _toHtml()
    {
        return sprintf('
        <script type=text/javascript>
            var UPG_PAYMENT_CONFIG = %s;
        </script>', json_encode(array(
            'BASE_URL' => $this->getBaseUrl()
        )));
    }
}