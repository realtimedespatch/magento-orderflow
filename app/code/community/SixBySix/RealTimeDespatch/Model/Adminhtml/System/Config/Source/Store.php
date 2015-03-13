<?php

/**
 * Store Source Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Source_Store
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
            $this->_options   = Mage::getResourceModel('core/store_collection')->load()->toOptionArray();
            $this->_options[] = array('value' => 0, 'label' => 'Default Store');
        }

        return $this->_options;
    }
}