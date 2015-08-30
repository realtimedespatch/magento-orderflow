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

        $this->_setupButtons();

        return parent::_toHtml();
    }

    /**
     * Sets up the admin buttons.
     *
     * @return void
     */
    protected function _setupButtons()
    {
        $this->_addButton(
            'process_request',
            array(
                'label'     => Mage::helper('realtimedespatch')->__('Process All Requests'),
                'onclick'   => "confirmSetLocation('Are you sure you wish to process this request?', '{$this->getUrl('*/*/processAll')}')",
                'class'     => 'go'
            ),
            0,
            -1
        );
    }
}