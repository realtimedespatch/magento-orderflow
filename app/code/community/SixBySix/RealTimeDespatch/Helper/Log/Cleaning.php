<?php

/**
 * Log Cleaning Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Log_Cleaning extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether log cleaning is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::helper('realtimedespatch')->isEnabled();
    }

    /**
     * Web Log Duration Getter.
     *
     * @return integer
     */
    public function getWebLogDuration()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/log_cleaning/web_log_duration');
    }

    /**
     * XML Log Duration Getter.
     *
     * @return integer
     */
    public function getXmlLogDuration()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/log_cleaning/xml_log_duration');
    }

    /**
     * Checks whether return closure is enabled.
     *
     * @return boolean
     */
    public function isReturnClosureEnabled()
    {
        if ( ! $this->isEnabled()) {
            return false;
        }

        return $this->getReturnDuration() > 0;
    }

    /**
     * Return Duration Getter.
     *
     * @return integer
     */
    public function getReturnDuration()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/log_cleaning/return_duration');
    }

    /**
     * Returns the cutoff date for returns.
     *
     * @return \Date|null
     */
    public function getReturnCutoffDate()
    {
        $date = new DateTime();
        $date->sub(new DateInterval('P'.$this->getReturnDuration().'D'));

        return $date;
    }
}