<?php

/**
 * Bulk Export Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Bulk_Export extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/bulk_export', 'entity_id');
    }
}