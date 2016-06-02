<?php
require_once Mage::getModuleDir('', 'Upg_Payments').'/Api/vendor/autoload.php';

$installer = $this;

$installer->startSetup();

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'upg_risk_class', array(
    'label' => "Type",
    'type' => 'int',
    'label' => 'Upg Risk Class',
    'input'    => 'select',
    'required' => false,
    'group' => 'General',
    'default' => Upg\Library\Risk\RiskClass::RISK_CLASS_DEFAULT,
    'is_global' => 1,
    'used_in_product_listing' => 0,
    'source' => 'upg_payments/sourceModels_riskClass'
));


$installer->endSetup();