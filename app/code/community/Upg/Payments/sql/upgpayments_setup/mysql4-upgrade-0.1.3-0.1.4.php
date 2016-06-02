<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('upg_payments/mns_message'));

$installer->getConnection()->addColumn($installer->getTable('upg_payments/mns_message'), 'mns_delay_processing', 'tinyint(1) unsigned default 0');
$installer->getConnection()->addIndex($installer->getTable('upg_payments/mns_message'), 'mns_delay_processing', array('mns_delay_processing'));

$installer->endSetup();