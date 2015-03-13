<?php

/* @var $installer Mage_Catalog_Model_Resource_Setup */

$installer = Mage::getResourceModel('catalog/setup','catalog_setup');
$installer->startSetup();

//remove unique status from OrderFlow catalogue attributes.

$orderFlowAttributes = array('barcode','weight_units','length','width','height','area','volume','storage_types',
    'tax_code');

foreach($orderFlowAttributes as $key)
{
    $installer->updateAttribute('catalog_product', $key, 'is_unique', false);
}

$installer->endSetup();