<?php

/**
 * Process Schedule Model.
 */
class SixBySix_RealTimeDespatch_Model_Process_Schedule extends Mage_Core_Model_Abstract
{
    /**
     * Event Prefix.
     *
     * @var string
     */
    protected $_eventPrefix = 'realtimedespatch_process_schedule';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/process_schedule');
    }
}