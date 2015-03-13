<?php

/**
 * Export Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Export extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/export', 'entity_id');
    }
}