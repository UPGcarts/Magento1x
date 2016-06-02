<?php

class Upg_Payments_Block_Recovery extends Mage_Core_Block_Template
{
    public function getIframeUrl()
    {
        return Mage::registry('paycoiframe');
    }
}