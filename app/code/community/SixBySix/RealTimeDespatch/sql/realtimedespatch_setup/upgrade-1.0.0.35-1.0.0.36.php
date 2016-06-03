<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn(
    $installer->getTable('realtimedespatch/import_line'),
    'additional_data',
    array(
        'type'     => Varien_Db_Ddl_Table::TYPE_BLOB,
        'nullable' => true,
        'comment'  => 'Additional Data',
    )
);

$installer->endSetup();