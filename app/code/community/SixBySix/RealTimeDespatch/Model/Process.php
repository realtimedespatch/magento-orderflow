<?php

/**
 * Process Model.
 */
class SixBySix_RealTimeDespatch_Model_Process extends Mage_Core_Model_Abstract
{
    /**
     * Event Prefix.
     *
     * @var string
     */
    protected $_eventPrefix = 'realtimedespatch_process';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/process');
    }

    /**
     * Returns the friendly name of the process.
     *
     * @return string
     */
    public function getFriendlyName()
    {
        return $this->getEntity()." ".$this->getType();
    }

    /**
     * Returns the helper responsible for the process.
     *
     * @return string
     */
    public function getHelperName()
    {
        return 'realtimedespatch/'.lcfirst($this->getType()).'_'.lcfirst($this->getEntity());
    }

    /**
     * Increments the current number of failures.
     *
     * @return \SixBySix_RealTimeDespatch_Model_Process
     */
    public function incrementFailures()
    {
        $this->setFailures($this->getFailures() + 1);

        return $this;
    }

    /**
     * Resets the current number of failures.
     *
     * @return \SixBySix_RealTimeDespatch_Model_Process
     */
    public function resetFailures()
    {
        $this->setFailures(0);

        return $this;
    }
}