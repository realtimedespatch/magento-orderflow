<?php

/**
 * Bulk Export Model.
 */
class SixBySix_RealTimeDespatch_Model_Bulk_Export extends Mage_Core_Model_Abstract
{
    const TYPE_CATALOG = 'catalog';

    /**
     * Model Event Prefix.
     *
     * @var string
     */
    protected $_eventPrefix = 'realtimedespatch_bulk_export';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/bulk_export');
    }
}