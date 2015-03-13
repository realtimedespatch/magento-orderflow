<?php

$installer = Mage::getResourceModel('catalog/setup','catalog_setup');
$installer->startSetup();

if ( ! $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, 'export_failures')) {
    $installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, 'export_failures', array(
        'label'                      => 'Export Failures',
        'sort_order'                 => 4,
        'backend'                    => '',
        'type'                       => 'int',
        'group'                      => 'OrderFlow',
        'frontend'                   => '',
        'note'                       => null,
        'default'                    => null,
        'wysiwyg_enabled'            => false,
        'input'                      => 'text',
        'source'                     => null,
        'required'                   => false,
        'user_defined'               => false,
        'unique'                     => false,
        'global'                     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'                    => true,
        'visible_on_front'           => false,
        'used_in_product_listing'    => false,
        'searchable'                 => false,
        'visible_in_advanced_search' => false,
        'filterable'                 => false,
        'filterable_in_search'       => false,
        'comparable'                 => false,
        'is_html_allowed_on_front'   => true,
        'apply_to'                   => 'simple',
        'is_configurable'            => false,
        'used_for_sort_by'           => false,
        'position'                   => 0,
        'used_for_promo_rules'       => false,
    ));
}

$installer->endSetup();