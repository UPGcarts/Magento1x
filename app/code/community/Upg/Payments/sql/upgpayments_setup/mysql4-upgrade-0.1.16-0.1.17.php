<?php

$installer = $this;

$installer->startSetup();

$installer->updateAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'upg_risk_class',
    'frontend_input_renderer',
    'upg_payments/adminhtml_form_field_renderer_riskClass'
);

$installer->endSetup();
