<?php

/**
 * Process Schedule Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Process_Schedule extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_process_schedule';

        parent::__construct();

        $this->_removeButton('add');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->_headerText = __('Schedule');

        return parent::_toHtml();
    }
}