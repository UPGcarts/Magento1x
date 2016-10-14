<?php

$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('upg_payments/transaction'), 'additional_information', 'TEXT');

$installer->endSetup();