<?php

$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->addColumn(
        $installer->getTable('realtimedespatch/process_schedule'),
        'executed_with_items',
            array(
                'type'     => Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
                'unsigned' => true,
                'nullable' => true,
                'comment'  => 'Executed With Items Timestamp'
            )
    );

$installer->endSetup();