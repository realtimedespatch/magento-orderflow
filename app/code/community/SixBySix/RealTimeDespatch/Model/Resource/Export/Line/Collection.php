<?php

/**
 * Export Line Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Export_Line_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/export_line');
    }
}