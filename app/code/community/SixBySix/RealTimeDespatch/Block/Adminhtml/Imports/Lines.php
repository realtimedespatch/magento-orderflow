<?php

/**
 * Imports Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Imports_Lines extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_imports_lines';
        $this->_headerText = Mage::helper('realtimedespatch')->__('Import Lines');

        parent::__construct();

        $this->_removeButton('add');
    }
}