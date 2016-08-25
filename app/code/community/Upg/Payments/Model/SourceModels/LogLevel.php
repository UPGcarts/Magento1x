<?php

class Upg_Payments_Model_SourceModels_LogLevel
{
    public function toOptionArray()
    {
        return array(
            array('value' => \Psr\Log\LogLevel::INFO, 'label' => Mage::helper('upg_payments')->__('Info')),
            array('value' => \Psr\Log\LogLevel::NOTICE, 'label' => Mage::helper('upg_payments')->__('Notice')),
            array('value' => \Psr\Log\LogLevel::WARNING, 'label' => Mage::helper('upg_payments')->__('Warning')),
            array('value' => \Psr\Log\LogLevel::ALERT, 'label' => Mage::helper('upg_payments')->__('Alert')),
            array('value' => \Psr\Log\LogLevel::ERROR, 'label' => Mage::helper('upg_payments')->__('Error')),
            array('value' => \Psr\Log\LogLevel::CRITICAL, 'label' => Mage::helper('upg_payments')->__('Critical')),
            array('value' => \Psr\Log\LogLevel::EMERGENCY, 'label' => Mage::helper('upg_payments')->__('Emergency')),
            array('value' => \Psr\Log\LogLevel::DEBUG, 'label' => Mage::helper('upg_payments')->__('Debug')),
        );
    }
}