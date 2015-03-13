<?php

/**
 * Inventory Import Cron Backend Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Backend_InventoryImport_Cron extends SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Backend_Cron
{
    const CRON_JOB_NAME = 'inventory_import';

    /**
     * {@inheritdoc}
     */
    protected function _getCronJobName()
    {
        return self::CRON_JOB_NAME;
    }
}