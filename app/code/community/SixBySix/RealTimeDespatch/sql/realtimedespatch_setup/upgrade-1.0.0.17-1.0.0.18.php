<?php

$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('realtimedespatch/import_line'), 'sequence_id', "varchar(30) null");
$installer->endSetup();