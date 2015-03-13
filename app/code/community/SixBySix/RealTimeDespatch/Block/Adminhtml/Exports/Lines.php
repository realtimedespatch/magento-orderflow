<?php

/**
 * Exports Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Exports_Lines extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_exports_lines';
        $this->_headerText = Mage::helper('realtimedespatch')->__('Export Lines');

        parent::__construct();

        $this->_removeButton('add');
    }
}