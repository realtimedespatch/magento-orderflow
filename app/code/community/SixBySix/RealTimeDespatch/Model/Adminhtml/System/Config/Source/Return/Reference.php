<?php

/**
 * Return Reference Source Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Source_Return_Reference
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
            $this->_options[] = array('value' => 0, 'label' => 'Order Increment Id');
            $this->_options[] = array('value' => 1, 'label' => 'RMA Increment Id');
        }

        return $this->_options;
    }
}