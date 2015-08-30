<?php

/**
 * Requests Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Requests extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_requests';

        parent::__construct();

        $this->_removeButton('add');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->_headerText = Mage::helper('realtimedespatch')->__('Requests');

        return parent::_toHtml();
    }
}