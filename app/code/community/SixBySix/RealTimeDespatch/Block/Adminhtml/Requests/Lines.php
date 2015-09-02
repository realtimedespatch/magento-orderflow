<?php

/**
 * Request LInes Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Requests_Lines extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_requests_lines';
        $this->_headerText = Mage::helper('realtimedespatch')->__('Request Lines');

        parent::__construct();

        $this->_removeButton('add');
    }
}