<?php

/**
 * Log Cleaning Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Log_Cleaner
{
    /**
     * Cleans the logs.
     *
     * @return SixBySix_RealTimeDespatch_Model_Service_Log_Cleaner
     */
    public function clean()
    {
        if ( ! Mage::helper('realtimedespatch/log_cleaning')->isEnabled()) {
            return $this;
        }

        $this->_cleanWebLogs();
        $this->_cleanXmlLogs();
    }

    /**
     * Cleans the web logs.
     *
     * @return void
     */
    protected function _cleanWebLogs()
    {
        $resource = Mage::getSingleton('core/resource');
        $write    = $resource->getConnection('core_write');
        $interval = Mage::helper('realtimedespatch/log_cleaning')->getWebLogDuration();

        $write->query("DELETE FROM ".$resource->getTableName('realtimedespatch/export')." WHERE DATEDIFF( NOW( ) , created ) > ".$write->quote($interval));
        $write->query("DELETE FROM ".$resource->getTableName('realtimedespatch/import')." WHERE DATEDIFF( NOW( ) , created ) > ".$write->quote($interval));
    }

    /**
     * Cleans the xml logs.
     *
     * @return void
     */
    protected function _cleanXmlLogs()
    {
        $resource = Mage::getSingleton('core/resource');
        $write    = $resource->getConnection('core_write');
        $interval = Mage::helper('realtimedespatch/log_cleaning')->getXmlLogDuration();

        $write->query("UPDATE ".$resource->getTableName('realtimedespatch/export')." SET request_body = NULL, response_body = NULL WHERE DATEDIFF( NOW( ) , created ) > ".$write->quote($interval));
        $write->query("UPDATE ".$resource->getTableName('realtimedespatch/import')." SET request_body = NULL, response_body = NULL WHERE DATEDIFF( NOW( ) , created ) > ".$write->quote($interval));
    }
}