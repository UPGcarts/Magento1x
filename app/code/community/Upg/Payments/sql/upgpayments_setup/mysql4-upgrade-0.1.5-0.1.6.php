<?php

$installer = $this;

$installer->startSetup();

$installer->getConnection()->modifyColumn($installer->getTable('upg_payments/transaction'), 'redirect_url','TEXT');

$installer->endSetup();