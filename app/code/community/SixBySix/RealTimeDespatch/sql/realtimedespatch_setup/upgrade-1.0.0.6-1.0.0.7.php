<?php

$installer = $this;
$installer->startSetup();

if ($installer->getConnection()->isTableExists($installer->getTable('realtimedespatch/import_line')) !== true) {
    $table = $installer->getConnection()->newTable(
        $installer->getTable('realtimedespatch/import_line'))
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
            'import_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'nullable' => false,
            ),
            'Import ID'
        )
        ->addColumn(
            'type',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            150,
            array(
                'nullable' => false,
            ),
            'Type'
        )
        ->addColumn(
            'reference',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            500,
            array(
                'nullable' => false,
            ),
            'Reference'
        )
        ->addColumn(
            'operation',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            50,
            array(
                'nullable' => false,
            ),
            'Operation Type'
        )
        ->addColumn(
            'entity',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            200,
            array(
                'nullable' => false,
            ),
            'Entity Type'
        )
        ->addColumn(
            'message',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            500,
            array(
                'nullable' => false,
            ),
            'Message'
        )
        ->addColumn(
            'detail',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            500,
            array(
                'nullable' => false,
            ),
            'Detail'
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
                'realtimedespatch/import',
                array('import_id')
            ),
            array('import_id')
        )
        ->addForeignKey(
            $installer->getFkName(
                'realtimedespatch/import_line',
                'import_id',
                'realtimedespatch/import',
                'entity_id'
            ),
            'import_id',
            $installer->getTable('realtimedespatch/import'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        );

    $installer->getConnection()->createTable($table);
    $installer->endSetup();
    }