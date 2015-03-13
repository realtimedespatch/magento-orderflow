<?php

$installer = Mage::getResourceModel('catalog/setup','catalog_setup');
$installer->startSetup();

$installer->updateAttribute('catalog_product', 'weight_units', 'default', 'gram');

$installer->endSetup();

Mage::getSingleton('catalog/product_action')->updateAttributes(
    Mage::getResourceModel('catalog/product_collection')->getAllIds(),
    array('weight_units' => 'gram'),
    Mage_Core_Model_App::ADMIN_STORE_ID
);