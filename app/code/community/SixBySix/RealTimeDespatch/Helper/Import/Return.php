<?php

/**
 * Return Import Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Import_Return extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the return import is enabled.
     *
     * @return boolean
     */
    public function isImportEnabled()
    {
        if ( ! Mage::helper('realtimedespatch')->isEnabled()) {
            return false;
        }

        return Mage::getStoreConfigFlag('sixbysix_realtimedespatch/return_import/is_enabled');
    }

    /**
     * Returns a list of importable return requests.
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Collection
     */
    public function getImportableRequests()
    {
        return Mage::getResourceModel('realtimedespatch/request_collection')->getProcessableRequestsWithType(
            SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_RETURN,
            $this->getBatchLimit()
        );
    }

    /**
     * Returns the next message ID.
     *
     * @return integer
     */
    public function getNextMessageId()
    {
        $msgId = 1;

        $lastRequest = Mage::getResourceModel('realtimedespatch/request_collection')->getLastRequestWithType(
            SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_RETURN
        );

        if ($lastRequest->getId()) {
            $msgId = $lastRequest->getMessageId() + 1;
        }

        return max(1, $msgId);
    }

    /**
     * Returns the current return import batch limit.
     *
     * @return integer
     */
    public function getBatchLimit()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/return_import/batch_size');
    }

    /**
     * Disables the return import cron process.
     *
     * @return void
     */
    public function disable()
    {
        Mage::getConfig()->saveConfig('sixbysix_realtimedespatch/return_import/is_enabled', false);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    /**
     * Staggers the return import cron process.
     *
     * @param integer $minutes
     *
     * @return void
     */
    public function stagger($minutes)
    {
        Mage::getSingleton('core/resource')
            ->getConnection('core_write')
            ->query("UPDATE ".Mage::getSingleton('core/resource')->getTableName('cron_schedule')." SET scheduled_at = DATE_ADD(scheduled_at, INTERVAL ".$minutes." MINUTE) WHERE job_code = 'orderflow_return_import' AND status = 'pending'");
    }
}
