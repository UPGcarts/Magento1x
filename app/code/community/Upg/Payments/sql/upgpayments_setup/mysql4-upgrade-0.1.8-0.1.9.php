<?php

// Add payment fee to Transaction table
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn(
    $installer->getTable('upg_payments/transaction'),
    'payment_fees',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'comment' => 'Payment Fees',
        'length' => '2M',
    )
);

$installer->endSetup();

// Add payment fee to quote, order, invoice and creditmemo
$installer = new Mage_Sales_Model_Resource_Setup('core_setup');

$installer->startSetup();

$entities = array(
    'quote',
    'order',
    'invoice',
    'creditmemo'
);

$options = array(
    'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'visible' => true,
    'required' => false
);

foreach ($entities as $entity) {
    $installer->addAttribute($entity, 'upg_payments_fee', $options);
    $installer->addAttribute($entity, 'upg_payments_base_fee', $options);
}

$installer->endSetup();
