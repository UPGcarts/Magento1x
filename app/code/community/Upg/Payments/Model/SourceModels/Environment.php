<?php

class Upg_Payments_Model_SourceModels_Environment
{
    const LIVE = 'LIVE';
    const TEST = 'TEST';

    public function toOptionArray()
    {
        return array(
            array('value' => self::TEST, 'label' => Mage::helper('upg_payments')->__('Test')),
            array('value' => self::LIVE, 'label' => Mage::helper('upg_payments')->__('Live')),
        );
    }
}