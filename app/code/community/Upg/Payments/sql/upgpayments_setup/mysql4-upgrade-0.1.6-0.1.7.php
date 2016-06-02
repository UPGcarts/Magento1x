<?php

$installer = $this;

$installer->startSetup();

/**
 * Add the attributes to the customer address entity
 */
$attributes = array(
    'upg_company_registration_id' => array(
        'label'    => 'Company Registration Id',
        'type'     => 'varchar',
        'input'    => 'text',
        'user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => 1,
        'sort_order'        => 140,
        'required'       => 0,
        'multiline_count'   => 0,
    ),
    'upg_company_registration_type' => array(
        'label'    => 'Company Registration Type',
        'type'     => 'varchar',
        'input'    => 'select',
        'user_defined'   => 0,
        'is_system'         => 0,
        'is_visible'        => 1,
        'sort_order'        => 140,
        'required'       => 0,
        'multiline_count'   => 0,
        'source' => 'upg_payments/sourceModels_registertype'
    )
);

foreach ($attributes as $attributeCode => $data) {
    $this->addAttribute('customer_address', $attributeCode, $data);
    Mage::getSingleton('eav/config')
        ->getAttribute('customer_address', $attributeCode)
        ->setData('used_in_forms', array('customer_register_address','customer_address_edit','adminhtml_customer_address'))
        ->save();
}

$connection = $installer->getConnection();

$connection->addColumn($installer->getTable('sales/quote_address'), 'upg_company_registration_id',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'length' => 255,
        'comment' => 'Payco Company Registration Id'
    ));

$connection->addColumn($installer->getTable('sales/quote_address'), 'upg_company_registration_type',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'length' => 255,
        'comment' => 'Payco Company Registration Type'
    ));

$connection->addColumn($installer->getTable('sales/order_address'), 'upg_company_registration_id',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'length' => 255,
        'comment' => 'Payco Company Registration Id'
    ));

$connection->addColumn($installer->getTable('sales/order_address'), 'upg_company_registration_type',
    array(
        'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
        'nullable' => true,
        'length' => 255,
        'comment' => 'Payco Company Registration Type'
    ));

$installer->endSetup();