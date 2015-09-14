<?php

$installer = $this;
$installer->startSetup();

if ($installer->getConnection()->isTableExists($installer->getTable('realtimedespatch/process_schedule')) !== true) {
    $table = $installer->getConnection()->newTable(
        $installer->getTable('realtimedespatch/process_schedule'))
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
            'Process'
        )
        ->addColumn(
            'entity',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            150,
            array(
                'nullable' => false,
            ),
            'Process'
        )
        ->addColumn(
            'cron_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'nullable' => true,
            ),
            'Cron ID'
        )
        ->addColumn(
            'parent_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned' => true,
                'nullable' => true,
            ),
            'Parent ID'
        )
        ->addColumn(
            'message_id',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            30,
            array(
                'unsigned' => true,
                'nullable' => true,
            ),
            'Message ID'
        )
        ->addColumn(
            'status',
            Varien_Db_Ddl_Table::TYPE_VARCHAR,
            150,
            array(
                'nullable' => false,
            ),
            'Status'
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
        ->addColumn(
            'scheduled',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(
                'nullable' => false,
            ),
            'Scheduled Timestamp'
        )
        ->addColumn(
            'executed',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(
                'nullable' => true,
            ),
            'Executed Timestamp'
        );

    $installer->getConnection()->createTable($table);
    $installer->endSetup();
}