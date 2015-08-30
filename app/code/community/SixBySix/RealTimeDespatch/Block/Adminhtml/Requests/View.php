<?php

/**
 * Requests Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Requests_View extends Mage_Adminhtml_Block_Widget_View_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_requests_view';

        parent::__construct();

        $this->_removeButton('edit');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderText()
    {
        return Mage::helper('realtimedespatch')->__('Request #'.$this->getOrderflowRequest()->getId());
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->getChild('plane')
            ->setOrderflowRequest($this->getOrderflowRequest())
            ->setChild('lines', $this->getLayout()->createBlock('realtimedespatch/adminhtml_requests_lines_grid'));

        return parent::_toHtml();
    }
}