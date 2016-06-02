<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('upg_payments/mns_message'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('merchant_id', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
        'nullable'  => false,
    ), 'Merchant Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Store ID')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Order ID')
    ->addColumn('capture_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Capture ID')
    ->addColumn('merchant_reference', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Merchant Reference')
    ->addColumn('payment_reference', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Payment Reference')
    ->addColumn('user_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'User ID')
    ->addColumn('amount', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'amount')
    ->addColumn('currency', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'currency')
    ->addColumn('transaction_status', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'transaction_status')
    ->addColumn('order_status', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'order_status')
    ->addColumn('additional_data', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        'nullable'  => false,
    ), 'additional data')
    ->addColumn('version', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'version')
    ->addColumn('mns_timestamp', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
        'nullable'  => false,
    ), 'Timestamp')
    ->addColumn('mns_processed', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Processed')
    ->addColumn('mns_error_processing', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Processed')
    ->addIndex($installer->getIdxName('upg_payments/mns_message', array('mns_processed')),
        array('mns_processed'))
    ->addIndex($installer->getIdxName('upg_payments/mns_message', array('mns_error_processing')),
        array('mns_error_processing'))
    ->addIndex($installer->getIdxName('upg_payments/mns_message', array('mns_processed', 'mns_error_processing')),
        array('mns_processed', 'mns_error_processing'))
    ->addIndex($installer->getIdxName('upg_payments/mns_message', array('mns_timestamp')),
        array('mns_timestamp'));
$installer->getConnection()->createTable($table);

$installer->endSetup();