<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable(
    $installer->getTable('realtimedespatch/process'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
            'primary'  => true,
            'identity' => true,
        ),
        'ID'
    )
    ->addColumn(
        'type',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        150,
        array(
            'nullable' => false,
        ),
        'Process Type'
    )
    ->addColumn(
        'entity',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,
        150,
        array(
            'nullable' => false,
        ),
        'Entity Type'
    )
    ->addColumn(
        'failures',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable' => false,
            'default'  => 0
        ),
        'Number of Failures'
    );

$installer->getConnection()->createTable($table);
$installer->endSetup();

$orderExportProcess = Mage::getModel('realtimedespatch/process');
$orderExportProcess->setType('Export');
$orderExportProcess->setEntity('Order');
$orderExportProcess->setFailures(0);
$orderExportProcess->save();

$productExportProcess = Mage::getModel('realtimedespatch/process');
$productExportProcess->setType('Export');
$productExportProcess->setEntity('Product');
$productExportProcess->setFailures(0);
$productExportProcess->save();