<?php

$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->addColumn(
        $installer->getTable('realtimedespatch/import'),
        'request_id',
            array(
                'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
                'unsigned' => true,
                'nullable' => true,
                'comment'  => 'Request ID'
            )
    );

$installer->endSetup();

// Truncate Cron
Mage::getSingleton('core/resource')
    ->getConnection('core_write')
    ->query("TRUNCATE ".Mage::getSingleton('core/resource')->getTableName('realtimedespatch_process_schedules'));