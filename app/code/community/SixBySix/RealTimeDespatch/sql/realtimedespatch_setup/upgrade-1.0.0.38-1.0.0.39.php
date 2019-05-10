<?php

$installer = $this;
$installer->startSetup();

$connection = $installer->getConnection();

$this->getConnection()->dropColumn(
    $installer->getTable('realtimedespatch/import'),
    'request_body'
);

$this->getConnection()->dropColumn(
    $installer->getTable('realtimedespatch/import'),
    'response_body'
);

$this->endSetup();