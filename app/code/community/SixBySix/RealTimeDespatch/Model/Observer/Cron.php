<?php

/**
 * Cron Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Cron
{
    /**
     * Executes the Product Export Service.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function executeProductExport($event)
    {
        $products = Mage::helper('realtimedespatch/export_product')->getExportableProducts();

        if (count($products) == 0) {
            return;
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_exporter');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Exporter::EXPORTER_PRODUCT_EXPORT);

        $service->export($products);
    }

    /**
     * Executes the Order Export Service.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function executeOrderExport($event)
    {
        $orders = Mage::helper('realtimedespatch/export_order')->getExportableOrders();

        if (count($orders) == 0) {
            return;
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_exporter');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Exporter::EXPORTER_ORDER_EXPORT);

        $service->export($orders);
    }

    /**
     * Executes the Shipment Import Service.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function executeShipmentImport($event)
    {
        $requests = Mage::helper('realtimedespatch/import_shipment')->getImportableRequests();

        if (count($requests) == 0) {
            return;
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_importer');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Importer::IMPORTER_SHIPMENT);

        foreach ($requests as $request) {
            $service->import(array($request));
        }
    }

    /**
     * Executes the Inventory Import Service.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function executeInventoryImport($event)
    {
        $requests = Mage::helper('realtimedespatch/import_inventory')->getImportableRequests();

        if (count($requests) == 0) {
            return;
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_importer');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Importer::IMPORTER_INVENTORY);

        $service->import($requests);
    }

    /**
     * Executes the Log Cleaning Service.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function executeLogCleaning($event)
    {
        Mage::getModel('realtimedespatch/service_log_cleaner')->clean();
    }
}