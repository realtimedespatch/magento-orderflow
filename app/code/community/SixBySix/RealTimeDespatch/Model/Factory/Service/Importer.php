<?php

/**
 * Importer Service Factory.
 */
class SixBySix_RealTimeDespatch_Model_Factory_Service_Importer
{
    const IMPORTER_INVENTORY = 'Inventory';
    const IMPORTER_SHIPMENT  = 'Shipment';

    /**
     * Retrieves an importer service.
     *
     * @param string $type
     */
    public function retrieve($type)
    {
        switch ($type) {
            case self::IMPORTER_INVENTORY:
                return new SixBySix_RealTimeDespatch_Model_Service_Importer_Inventory;
            break;
            case self::IMPORTER_SHIPMENT:
                return new SixBySix_RealTimeDespatch_Model_Service_Importer_Shipment;
            break;
            default:
                throw new Exception('Invalid Importer Type');
            break;
        }
    }
}