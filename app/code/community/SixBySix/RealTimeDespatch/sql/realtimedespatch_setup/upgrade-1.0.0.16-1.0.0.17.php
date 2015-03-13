<?php

$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('realtimedespatch/import'), 'message_id', "varchar(30) null");
$installer->endSetup();