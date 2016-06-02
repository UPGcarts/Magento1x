<?php

$installer = $this;

$installer->startSetup();

$installer->getTable('sales/invoice');

$installer->getConnection()->addColumn($installer->getTable('upg_payments/transaction'), 'redirect_url',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'comment' => 'Redirect Url',
        'length' => 255,
    ));

$installer->endSetup();