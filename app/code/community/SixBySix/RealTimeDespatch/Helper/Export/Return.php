<?php

/**
 * Return Export Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Export_Return extends Mage_Core_Helper_Abstract
{
    const REFERENCE_ORDER_ID = 0;

    /**
     * Checks whether the return export is enabled.
     *
     * @return boolean
     */
    public function isExportEnabled()
    {
        if ( ! Mage::helper('realtimedespatch')->isEnabled()) {
            return false;
        }

        return Mage::getStoreConfigFlag('sixbysix_realtimedespatch/return_export/is_enabled');
    }

    /**
     * Returns the return site parameter..
     *
     * @return integer
     */
    public function getSite()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/return_export/site');
    }

    /**
     * Returns the appropriate authorisation reference.
     *
     * @param Enteprise_Rma_Model_Rma $rma
     *
     * @return string
     */
    public function getAuthorisationReference($rma)
    {
        if ($this->getReturnReference() == self::REFERENCE_ORDER_ID) {
            return $rma->getOrderIncrementId();
        }

        return $rma->getIncrementId();
    }

    /**
     * Returns the return site parameter..
     *
     * @return integer
     */
    public function getReturnReference()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/return_export/return_reference');
    }

    /**
     * Returns the current product export batch limit.
     *
     * @return integer
     */
    public function getBatchLimit()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/return_export/batch_size');
    }

    /**
     * Returns a list of returns that can be exported.
     *
     * @return Enterprise_Rma_Model_Resource_Rma_Collection
     */
    public function getExportableReturns()
    {
        $exportableReturns = array();
        $authorizedReturns = Mage::getResourceModel('enterprise_rma/rma_collection')
            ->addFieldToFilter('status', array('in' => array('authorized', 'partially_authorized')))
            ->addFieldToFilter('is_exported', array('eq' => 0))
            ->addFieldToFilter('export_failures', array('lt' => 4))
            ->setPageSize($this->getBatchLimit())
            ->setCurPage(1);

        foreach ($authorizedReturns as $authorizedReturn) {
            if ($authorizedReturn->isExportable()) {
                $exportableReturns[] = $authorizedReturn;
            }
        }

        return $exportableReturns;
    }

    /**
     * Disables the return export cron process.
     *
     * @return void
     */
    public function disable()
    {
        Mage::getConfig()->saveConfig('sixbysix_realtimedespatch/return_export/is_enabled', false);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    /**
     * Staggers the product export cron process.
     *
     * @param integer $minutes
     *
     * @return void
     */
    public function stagger($minutes)
    {
        Mage::getSingleton('core/resource')
            ->getConnection('core_write')
            ->query("UPDATE ".Mage::getSingleton('core/resource')->getTableName('cron_schedule')." SET scheduled_at = DATE_ADD(scheduled_at, INTERVAL ".$minutes." MINUTE) WHERE job_code = 'orderflow_return_export' AND status = 'pending'");
    }
}