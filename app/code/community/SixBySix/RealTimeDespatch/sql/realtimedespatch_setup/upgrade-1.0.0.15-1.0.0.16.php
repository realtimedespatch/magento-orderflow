<?php

$installer = $this;
$installer->startSetup();

$requestTableName = $installer->getTable('realtimedespatch/request');
$requestIndexName = 'UNQ_'.strtoupper($requestTableName).'_MESSAGE_ID_TYPE';

$installer->getConnection()
->dropIndex(
    $requestTableName,
    $requestIndexName
);

$requestLinesTableName = $installer->getTable('realtimedespatch/request_line');
$requestLinesIndexName = 'UNQ_'.strtoupper($requestLinesTableName).'_SEQUENCE_ID_TYPE';

$installer->getConnection()
->dropIndex(
    $requestLinesTableName,
    $requestLinesIndexName
);

$installer->endSetup();