<?php

/**
 * Import Line Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Import_Line_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/import_line');
    }
}