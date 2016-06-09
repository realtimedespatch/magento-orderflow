<?php

/**
 * Inventory Calculation Source Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Source_Inventory_Calculation
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
            $this->_options[] = array('value' => 0, 'label' => 'No');
            $this->_options[] = array('value' => 1, 'label' => 'Unsent Orders');
            $this->_options[] = array('value' => 2, 'label' => 'Unsent Orders and Active Quotes');
        }

        return $this->_options;
    }
}