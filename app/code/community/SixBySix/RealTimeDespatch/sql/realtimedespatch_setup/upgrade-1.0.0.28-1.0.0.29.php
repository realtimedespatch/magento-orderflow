<?php

$installer = $this;
$installer->startSetup();

if ($installer->getConnection()->isTableExists($installer->getTable('realtimedespatch/bulk_export')) !== true) {
    $table = $installer->getConnection()->newTable(
        $installer->getTable('realtimedespatch/bulk_export'))
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
            'executed',
            Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
            null,
            array(
                'nullable' => true,
            ),
            'Executed Timestamp'
        );

    $installer->getConnection()->createTable($table);

    $installer->getConnection()->addIndex(
        $installer->getTable('realtimedespatch/bulk_export'),
        $installer->getIdxName(
            'realtimedespatch/bulk_export',
            array(
                'type',
            ),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array(
            'type'
        ),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    );

    $installer->endSetup();

    $export = Mage::getModel('realtimedespatch/bulk_export');
    $export->setType(SixBySix_RealTimeDespatch_Model_Bulk_Export::TYPE_CATALOG);
    $export->save();
}