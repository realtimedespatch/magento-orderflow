<?php

/**
 * Shipment Import Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Import_Shipment extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the shipment import is enabled.
     *
     * @return boolean
     */
    public function isImportEnabled()
    {
        if ( ! Mage::helper('realtimedespatch')->isEnabled()) {
            return false;
        }

        return Mage::getStoreConfigFlag('sixbysix_realtimedespatch/shipment_import/is_enabled');
    }

    /**
     * Returns the current shipment import batch limit.
     *
     * @return integer
     */
    public function getBatchLimit()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/shipment_import/batch_size');
    }

    /**
     * Returns a list of importable shipment requests.
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Collection
     */
    public function getImportableRequests()
    {
        return Mage::getResourceModel('realtimedespatch/request_collection')->getProcessableRequestsWithType(
            SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_SHIPMENT,
            $this->getBatchLimit()
        );
    }

    /**
     * Disables the shipment import cron process.
     *
     * @return void
     */
    public function disable()
    {
        Mage::getConfig()->saveConfig('sixbysix_realtimedespatch/shipment_import/is_enabled', false);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    /**
     * Staggers the shipment import cron process.
     *
     * @param integer $minutes
     *
     * @return void
     */
    public function stagger($minutes)
    {
        Mage::getSingleton('core/resource')
            ->getConnection('core_write')
            ->query("UPDATE ".Mage::getSingleton('core/resource')->getTableName('cron_schedule')." SET scheduled_at = DATE_ADD(scheduled_at, INTERVAL ".$minutes." MINUTE) WHERE job_code = 'shipment_import' AND status = 'pending'");
    }
}