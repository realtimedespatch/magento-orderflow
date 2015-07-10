<?php

	require_once '../app/Mage.php';
	Mage::app();
	Mage::getModel('realtimedespatch/service_validation')->checkExportedOrders();
