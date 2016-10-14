<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
    ->addColumn(
        $installer->getTable('upg_payments/update_transaction'),
        'ut_invoice_id',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'comment' => 'Update Transaction Invoice ID'
        ));

$installer->endSetup();

$installer = new Mage_Sales_Model_Resource_Setup('core_setup');

$installer->startSetup();

$options = array(
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length' => 255,
    'visible' => true,
    'required' => false
);

$installer->addAttribute('invoice', 'upg_payments_ut_invoice_id', $options);

$installer->endSetup();