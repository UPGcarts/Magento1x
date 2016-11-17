<?php

$installer = new Mage_Sales_Model_Resource_Setup('core_setup');

$installer->startSetup();

$options = array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'visible' => true,
    'required' => false
);

$installer->addAttribute('invoice', 'upg_payments_fee_total_refunded', $options);
$installer->addAttribute('invoice', 'upg_payments_base_fee_total_refunded', $options);
$installer->addAttribute('creditmemo', 'upg_payments_fee_refunded', $options);
$installer->addAttribute('creditmemo', 'upg_payments_base_fee_refunded', $options);

$installer->endSetup();

