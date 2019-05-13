<?php

$installer = $this;
$installer->startSetup();

// Enable Symlinks

Mage::getConfig()->saveConfig('dev/template/allow_symlink', '1', 'default', 0);

// Apply Performance Indexes

$connection->addIndex(
    $installer->getTable('realtimedespatch/request'),
    $installer->getIdxName(
        'realtimedespatch/request',
        array('type'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('type'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/request'),
    $installer->getIdxName(
        'realtimedespatch/request',
        array('message_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('message_id'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/request_line'),
    $installer->getIdxName(
        'realtimedespatch/request_line',
        array('processed'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('processed'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/request_line'),
    $installer->getIdxName(
        'realtimedespatch/request_line',
        array('created'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('created'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);

$connection->addIndex(
    $installer->getTable('realtimedespatch/request_line'),
    $installer->getIdxName(
        'realtimedespatch/request_line',
        array('sequence_id', 'processed', 'type'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
    ),
    array('sequence_id', 'processed', 'type'),
    Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
);