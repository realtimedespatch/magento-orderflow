<?php

$installer = Mage::getResourceModel('catalog/setup','catalog_setup');
$installer->startSetup();

foreach ($installer->getAllAttributeSetIds(Mage_Sales_Model_Order::ENTITY) as $setId) {
    if ( ! $installer->getAttributeGroup(Mage_Sales_Model_Order::ENTITY, $setId, 'OrderFlow')) {
        $installer->addAttributeGroup(Mage_Sales_Model_Order::ENTITY, $setId, 'OrderFlow');
    }
}

if ( ! $installer->getAttributeId(Mage_Sales_Model_Order::ENTITY, 'export_failures')) {
    $installer->addAttribute(Mage_Sales_Model_Order::ENTITY, 'export_failures', array(
        'label'                      => 'Export Failures',
        'sort_order'                 => 1,
        'backend'                    => '',
        'type'                       => 'static',
        'group'                      => 'OrderFlow',
        'frontend'                   => '',
        'note'                       => null,
        'default'                    => 0,
        'wysiwyg_enabled'            => false,
        'input'                      => 'int',
        'source'                     => null,
        'required'                   => true,
        'user_defined'               => false,
        'unique'                     => false,
        'visible'                    => true,
        'visible_on_front'           => false,
        'used_in_product_listing'    => false,
        'searchable'                 => false,
        'visible_in_advanced_search' => false,
        'filterable'                 => false,
        'filterable_in_search'       => false,
        'comparable'                 => false,
        'is_html_allowed_on_front'   => true,
        'is_configurable'            => false,
        'used_for_sort_by'           => false,
        'position'                   => 0,
        'used_for_promo_rules'       => false,
    ));
}

$installer->getConnection()->addColumn($installer->getTable('sales/order'), 'export_failures', "tinyint(3) DEFAULT '0'");
$installer->getConnection()->addColumn($installer->getTable('sales/order_grid'), 'export_failures', "tinyint(3) DEFAULT '0'");

$installer->endSetup();

// Set initial export_failures value to false via direct sql query for performance.

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$write->query("UPDATE ".$installer->getTable('sales/order')." SET export_failures = 0;");
$write->query("UPDATE ".$installer->getTable('sales/order_grid')." SET export_failures = 0;");