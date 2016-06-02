<?php

$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('upg_payments/transaction'), 'recovery_url',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'comment' => 'Recovery Url',
        'length' => 255,
    ));

$installer->endSetup();