<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable(
    $installer->getTable('realtimedespatch/import'))
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
        'Export Type'
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
        'successes',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable' => false,
            'default'  => 0
        ),
        'Export Successes'
    )
    ->addColumn(
        'duplicates',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable' => false,
            'default'  => 0
        ),
        'Export Duplicates'
    )
    ->addColumn(
        'failures',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable' => false,
            'default'  => 0
        ),
        'Export Failures'
    )
    ->addColumn(
        'created',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => false,
            'default'  => Varien_Db_Ddl_Table::TIMESTAMP_INIT
        ),
        'Created Timestamp'
    );

$installer->getConnection()->createTable($table);
$installer->endSetup();