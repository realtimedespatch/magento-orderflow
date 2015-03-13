<?php

/**
 * Exports Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Exports extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_exports';

        parent::__construct();

        $this->_removeButton('add');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->_headerText = Mage::helper('realtimedespatch')->__($this->getEntityType().' Exports');

        $this->getChild('grid')
              ->setOperationType($this->getOperationType())
              ->setEntityType($this->getEntityType())
              ->setReferenceLabel($this->getReferenceLabel());

        return parent::_toHtml();
    }
}