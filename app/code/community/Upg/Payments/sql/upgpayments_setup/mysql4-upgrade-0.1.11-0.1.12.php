<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('upg_payments/update_transaction'))
    ->addColumn('ut_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
    ), 'Id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Order ID')
    ->addColumn('ut_timestamp', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
        'nullable'  => false,
    ), 'Timestamp')
    ->addColumn('ut_processed', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Processed')
    ->addColumn('ut_error_processing', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'default'   => '0',
    ), 'Processed')
    ->addIndex($installer->getIdxName('upg_payments/update_transaction', array('ut_processed')),
        array('ut_processed'))
    ->addIndex($installer->getIdxName('upg_payments/update_transaction', array('ut_error_processing')),
        array('ut_error_processing'))
    ->addIndex($installer->getIdxName('upg_payments/update_transaction', array('ut_processed', 'ut_error_processing')),
        array('ut_processed', 'ut_error_processing'))
    ->addIndex($installer->getIdxName('upg_payments/update_transaction', array('ut_timestamp')),
        array('ut_timestamp'));

$installer->getConnection()->createTable($table);
$installer->endSetup();
