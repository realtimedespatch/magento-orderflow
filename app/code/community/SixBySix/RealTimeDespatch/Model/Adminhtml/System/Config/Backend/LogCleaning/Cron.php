<?php

/**
 * Log Cleaning Cron Backend Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Backend_LogCleaning_Cron extends SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Backend_Cron
{
    const CRON_JOB_NAME = 'log_cleaning';

    /**
     * {@inheritdoc}
     */
    protected function _getCronJobName()
    {
        return self::CRON_JOB_NAME;
    }
}