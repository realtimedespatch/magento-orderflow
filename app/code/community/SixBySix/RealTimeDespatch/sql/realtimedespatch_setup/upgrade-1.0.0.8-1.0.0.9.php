<?php

$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()->newTable(
    $installer->getTable('realtimedespatch/request_line'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_BIGINT,
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
        'request_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
        ),
        'Request ID'
    )
    ->addColumn(
        'sequence_id',
        Varien_Db_Ddl_Table::TYPE_BIGINT,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
        ),
        'Sequence ID'
    )
    ->addColumn(
        'type',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        10,
        array(
            'nullable' => false,
        ),
        'Request Type'
    )
    ->addColumn(
        'body',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        65535,
        array(
            'nullable' => false,
        ),
        'Request Body'
    )
    ->addColumn(
        'processed',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(
            'nullable' => true,
        ),
        'Processed Timestamp'
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
    )
    ->addIndex(
        $installer->getIdxName(
            'realtimedespatch/request_line',
            array(
                'sequence_id',
                'type',
            ),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array(
            'sequence_id',
            'type',
        ),
        array(
            'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        )
    )
    ->addIndex(
        $installer->getIdxName(
            'realtimedespatch/request_line',
            array('sequence_id')
        ),
        array('sequence_id')
    )
    ->addIndex(
        $installer->getIdxName(
            'realtimedespatch/request_line',
            array('type')
        ),
        array('type')
    )
    ->addIndex(
        $installer->getIdxName(
            'realtimedespatch/request_line',
            array('request_id')
        ),
        array('request_id')
    )
    ->addForeignKey(
        $installer->getFkName(
            'realtimedespatch/request_line',
            'request_id',
            'realtimedespatch/request',
            'entity_id'
        ),
        'request_id',
        $installer->getTable('realtimedespatch/request'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    );

$installer->getConnection()->createTable($table);
$installer->endSetup();