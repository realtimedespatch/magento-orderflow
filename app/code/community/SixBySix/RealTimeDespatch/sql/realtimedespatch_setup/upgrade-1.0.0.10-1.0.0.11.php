<?php

$orderExportProcess = Mage::getModel('realtimedespatch/process');
$orderExportProcess->setType('Import');
$orderExportProcess->setEntity('Inventory');
$orderExportProcess->setFailures(0);
$orderExportProcess->save();

$productExportProcess = Mage::getModel('realtimedespatch/process');
$productExportProcess->setType('Import');
$productExportProcess->setEntity('Shipment');
$productExportProcess->setFailures(0);
$productExportProcess->save();