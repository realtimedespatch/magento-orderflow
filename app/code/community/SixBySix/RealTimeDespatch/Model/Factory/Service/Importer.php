<?php

/**
 * Importer Service Factory.
 */
class SixBySix_RealTimeDespatch_Model_Factory_Service_Importer
{
    const IMPORTER_INVENTORY = 'Inventory';
    const IMPORTER_SHIPMENT  = 'Shipment';
    const IMPORTER_RETURN  = 'Return';

    /**
     * Retrieves an importer service.
     *
     * @param string $type
     */
    public function retrieve($type)
    {
        switch ($type) {
            case self::IMPORTER_INVENTORY:
                return Mage::getModel('realtimedespatch/service_importer_inventory');
            break;
            case self::IMPORTER_SHIPMENT:
                return Mage::getModel('realtimedespatch/service_importer_shipment');
            break;
            case self::IMPORTER_RETURN:
                return Mage::getModel('realtimedespatch/service_importer_return');
                break;
            default:
                throw new Exception('Invalid Importer Type');
            break;
        }
    }
}