<?php

class Upg_Payments_Model_SourceModels_LogLevel
{
    public function toOptionArray()
    {
        return array(
            array('value' => \Psr\Log\LogLevel::INFO, 'label' => 'Info'),
            array('value' => \Psr\Log\LogLevel::NOTICE, 'label' => 'Notice'),
            array('value' => \Psr\Log\LogLevel::WARNING, 'label' => 'Warning'),
            array('value' => \Psr\Log\LogLevel::ALERT, 'label' => 'Alert'),
            array('value' => \Psr\Log\LogLevel::ERROR, 'label' => 'Error'),
            array('value' => \Psr\Log\LogLevel::CRITICAL, 'label' => 'Critical'),
            array('value' => \Psr\Log\LogLevel::EMERGENCY, 'label' => 'Emergency'),
            array('value' => \Psr\Log\LogLevel::DEBUG, 'label' => 'Debug'),
        );
    }
}