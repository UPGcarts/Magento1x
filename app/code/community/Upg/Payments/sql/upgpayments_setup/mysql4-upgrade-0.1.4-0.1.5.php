<?php

$installer = $this;

$installer->startSetup();

$installer->getConnection()->modifyColumn($installer->getTable('upg_payments/transaction'), 'recovery_url','TEXT');

$installer->endSetup();