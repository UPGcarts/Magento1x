<?php
/**
 * @var $installer Mage_Eav_Model_Entity_Setup
 */
$installer = $this;
$installer->startSetup();

$transactionTable = $installer->getTable('upg_payments/transaction');
$prefix = Mage::getConfig()->getTablePrefix();

$sql = "CREATE TABLE IF NOT EXISTS {$transactionTable}(
        entity_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        order_ref varchar(30),
        autocapture TINYINT(1),
        payment_method VARCHAR(30),
        payment_instrument_id VARCHAR(50),
        PRIMARY KEY (entity_id),
        INDEX `{$prefix}PAYCO_TRANSACTION_ORDER_REF_IDX` (`order_ref`)
        )ENGINE=INNODB;";

$installer->run($sql);

$installer->endSetup();

