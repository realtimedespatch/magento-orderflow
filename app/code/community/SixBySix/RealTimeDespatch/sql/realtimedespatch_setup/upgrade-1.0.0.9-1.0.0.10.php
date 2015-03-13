<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn(
    $installer->getTable('realtimedespatch/import'),
    'request_body',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BLOB,
        'nullable' => true,
        'comment'  => 'Request Body',
    )
);

$installer->getConnection()
->addColumn(
    $installer->getTable('realtimedespatch/import'),
    'response_body',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BLOB,
        'nullable' => true,
        'comment'  => 'Response Body',
    )
);

$installer->getConnection()
->addColumn(
    $installer->getTable('realtimedespatch/request'),
    'request_body',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BLOB,
        'nullable' => true,
        'comment'  => 'Request Body',
    )
);

$installer->getConnection()
->addColumn(
    $installer->getTable('realtimedespatch/request'),
    'response_body',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BLOB,
        'nullable' => true,
        'comment'  => 'Response Body',
    )
);

$installer->endSetup();