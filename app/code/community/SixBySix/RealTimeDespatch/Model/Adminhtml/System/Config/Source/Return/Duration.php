<?php

/**
 * Return Duration Source Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Source_Return_Duration
{
    /**
     * Options.
     *
     * @var array
     */
    protected $_options;

    /**
     * Returns the options array.
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ( ! $this->_options) {
            $this->_options   = array();
            $this->_options[] = array('value' => 0, 'label' => 'Disabled');
            $this->_options[] = array('value' => 30, 'label' => '30 Days');
            $this->_options[] = array('value' => 60, 'label' => '60 Days');
            $this->_options[] = array('value' => 90, 'label' => '90 Days');
            $this->_options[] = array('value' => 120, 'label' => '120 Days');
            $this->_options[] = array('value' => 150, 'label' => '150 Days');
            $this->_options[] = array('value' => 180, 'label' => '180 Days');
        }

        return $this->_options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            0   => 'Disabled',
            30  => 30,
            60  => 60,
            90  => 90,
            120 => 120,
            150 => 150,
            180 => 180,
        );
    }
}