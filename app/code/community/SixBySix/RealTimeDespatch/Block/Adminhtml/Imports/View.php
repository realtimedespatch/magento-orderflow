<?php

/**
 * Imports Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Imports_View extends Mage_Adminhtml_Block_Widget_View_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_imports_view';

        parent::__construct();

        $this->_removeButton('edit');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderText()
    {
        return Mage::helper('realtimedespatch')->__($this->getImport()->getEntityType().' Import #'.$this->getImport()->getId());
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->getChild('plane')
            ->setImport($this->getImport())
            ->setReferenceLabel($this->getReferenceLabel())
            ->setChild('lines', $this->getLayout()->createBlock('realtimedespatch/adminhtml_imports_lines_grid'));

        return parent::_toHtml();
    }
}