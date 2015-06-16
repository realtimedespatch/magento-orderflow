<?php

/**
 * Inventory Import Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Import_Inventory extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the inventory import is enabled.
     *
     * @return boolean
     */
    public function isImportEnabled()
    {
        if ( ! Mage::helper('realtimedespatch')->isEnabled()) {
            return false;
        }

        return Mage::getStoreConfigFlag('sixbysix_realtimedespatch/inventory_import/is_enabled');
    }

    /**
     * Returns the current inventory import batch limit.
     *
     * @return integer
     */
    public function getBatchLimit()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/inventory_import/batch_size');
    }

    /**
     * Returns a list of importable inventory requests.
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Collection
     */
    public function getImportableRequests()
    {
        return Mage::getResourceModel('realtimedespatch/request_line_collection')->getNextRequestLines(
            SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_INVENTORY,
            $this->getBatchLimit()
        );
    }

    /**
     * Checks whether negative inventory quantities are enabled.
     *
     * @return boolean
     */
    public function isNegativeQtyEnabled()
    {
        return (boolean) Mage::getStoreConfig('sixbysix_realtimedespatch/inventory_import/negative_qtys_enabled');
    }

    /**
     * Disables the inventory import cron process.
     *
     * @return void
     */
    public function disable()
    {
        Mage::getConfig()->saveConfig('sixbysix_realtimedespatch/inventory_import/is_enabled', false);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    /**
     * Staggers the inventory import cron process.
     *
     * @param integer $minutes
     *
     * @return void
     */
    public function stagger($minutes)
    {
        Mage::getSingleton('core/resource')
            ->getConnection('core_write')
            ->query("UPDATE ".Mage::getSingleton('core/resource')->getTableName('cron_schedule')." SET scheduled_at = DATE_ADD(scheduled_at, INTERVAL ".$minutes." MINUTE) WHERE job_code = 'inventory_import' AND status = 'pending'");
    }
}