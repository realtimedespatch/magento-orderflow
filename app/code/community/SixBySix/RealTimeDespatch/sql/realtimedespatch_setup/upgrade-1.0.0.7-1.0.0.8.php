<?php

$installer = $this;
$installer->startSetup();

if ($installer->getConnection()->isTableExists($installer->getTable('realtimedespatch/request')) !== true) {
    $table = $installer->getConnection()->newTable(
        $installer->getTable('realtimedespatch/request'))
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
            'message_id',
            Varien_Db_Ddl_Table::TYPE_BIGINT,
            null,
            array(
                'unsigned' => true,
                'nullable' => false,
            ),
            'Message ID'
        )
        ->addColumn(
            'type',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            150,
            array(
                'nullable' => false,
            ),
            'Request Type'
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
                'realtimedespatch/request',
                array(
                    'message_id',
                    'type',
                ),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array(
                'message_id',
                'type',
            ),
            array(
                'type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            )
        )    ;

    $installer->getConnection()->createTable($table);
    $installer->endSetup();
}