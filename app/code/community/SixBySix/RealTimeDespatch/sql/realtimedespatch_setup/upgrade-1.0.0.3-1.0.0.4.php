<?php

$installer = Mage::getResourceModel('catalog/setup','catalog_setup');
$installer->startSetup();

foreach ($installer->getAllAttributeSetIds(Mage_Sales_Model_Order::ENTITY) as $setId) {
    if ( ! $installer->getAttributeGroup(Mage_Sales_Model_Order::ENTITY, $setId, 'OrderFlow')) {
        $installer->addAttributeGroup(Mage_Sales_Model_Order::ENTITY, $setId, 'OrderFlow');
    }
}

if ( ! $installer->getAttributeId(Mage_Sales_Model_Order::ENTITY, 'is_exported')) {
    $installer->addAttribute(Mage_Sales_Model_Order::ENTITY, 'is_exported', array(
        'label'                      => 'Exported',
        'sort_order'                 => 1,
        'backend'                    => '',
        'type'                       => 'static',
        'group'                      => 'OrderFlow',
        'frontend'                   => '',
        'note'                       => null,
        'default'                    => 0,
        'wysiwyg_enabled'            => false,
        'input'                      => 'boolean',
        'source'                     => 'eav/entity_attribute_source_boolean',
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

if ( ! $installer->getAttributeId(Mage_Sales_Model_Order::ENTITY, 'exported_at')) {
    $installer->addAttribute(Mage_Sales_Model_Order::ENTITY, 'exported_at', array(
        'label'                      => 'Exported Timestamp',
        'sort_order'                 => 1,
        'backend'                    => '',
        'type'                       => 'static',
        'group'                      => 'OrderFlow',
        'frontend'                   => 'eav/entity_attribute_frontend_datetime',
        'note'                       => null,
        'default'                    => null,
        'wysiwyg_enabled'            => false,
        'input'                      => 'text',
        'source'                     => null,
        'required'                   => false,
        'user_defined'               => false,
        'unique'                     => false,
        'visible'                    => false,
        'visible_on_front'           => false,
        'used_in_product_listing'    => false,
        'searchable'                 => false,
        'visible_in_advanced_search' => false,
        'filterable'                 => false,
        'filterable_in_search'       => false,
        'comparable'                 => false,
        'is_html_allowed_on_front'   => false,
        'is_configurable'            => false,
        'used_for_sort_by'           => false,
        'position'                   => 0,
        'used_for_promo_rules'       => false,
    ));
}

$installer->getConnection()->addColumn($installer->getTable('sales/order'), 'is_exported', "tinyint(1) DEFAULT '0'");
$installer->getConnection()->addColumn($installer->getTable('sales/order'), 'exported_at', 'datetime');
$installer->getConnection()->addColumn($installer->getTable('sales/order_grid'), 'is_exported', "tinyint(1) DEFAULT '0'");
$installer->getConnection()->addColumn($installer->getTable('sales/order_grid'), 'exported_at', 'datetime');

$installer->endSetup();

// Set initial is_exported value to false via direct sql query for performance.

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$write->query("UPDATE ".$installer->getTable('sales/order')." SET is_exported = 0;");
$write->query("UPDATE ".$installer->getTable('sales/order_grid')." SET is_exported = 0;");