<?php

$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();

// Sales Flat Order Indexes

$connection->addIndex(
    $installer->getTable('sales/order'),
    $installer->getIdxName(
        'realtimedespatch/rtd_imports',
        array('is_virtual', 'exported_at', 'is_exported', 'updated_at', 'status'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('is_virtual', 'exported_at', 'is_exported', 'updated_at', 'status'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

// Import Line Indexes

$connection->modifyColumn(
    $installer->getTable('realtimedespatch/import_line'),
    'reference',
    'varchar(150)'
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/import_line'),
    $installer->getIdxName(
        'realtimedespatch/rtd_imports',
        array('sequence_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('sequence_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/import_line'),
    $installer->getIdxName(
        'realtimedespatch/rtd_imports',
        array('reference'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('reference'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/import_line'),
    $installer->getIdxName(
        'realtimedespatch/rtd_imports',
        array('reference', 'processed'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('reference', 'processed'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/import_line'),
    $installer->getIdxName(
        'realtimedespatch/rtd_imports',
        array('reference', 'processed', 'sequenced_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('reference', 'processed', 'sequenced_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);