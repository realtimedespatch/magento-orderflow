<?php

$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();

$installer->getConnection()->addColumn($installer->getTable('enterprise_rma/rma'), 'is_exported', "tinyint(1) DEFAULT '0'");
$installer->getConnection()->addColumn($installer->getTable('enterprise_rma/rma'), 'exported_at', 'datetime');
$installer->getConnection()->addColumn($installer->getTable('enterprise_rma/rma_grid'), 'is_exported', "tinyint(1) DEFAULT '0'");
$installer->getConnection()->addColumn($installer->getTable('enterprise_rma/rma_grid'), 'exported_at', 'datetime');
$installer->getConnection()->addColumn($installer->getTable('enterprise_rma/rma'), 'export_failures', "tinyint(3) DEFAULT '0'");
$installer->getConnection()->addColumn($installer->getTable('enterprise_rma/rma_grid'), 'export_failures', "tinyint(3) DEFAULT '0'");

$installer->endSetup();

$write = Mage::getSingleton('core/resource')->getConnection('core_write');

$write->query("UPDATE ".$installer->getTable('enterprise_rma/rma')." SET is_exported = 0;");
$write->query("UPDATE ".$installer->getTable('enterprise_rma/rma_grid')." SET is_exported = 0;");
$write->query("UPDATE ".$installer->getTable('enterprise_rma/rma')." SET export_failures = 0;");
$write->query("UPDATE ".$installer->getTable('enterprise_rma/rma_grid')." SET export_failures = 0;");