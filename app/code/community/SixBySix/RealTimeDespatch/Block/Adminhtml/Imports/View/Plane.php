<?php

/**
 * Imports View Details Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Imports_View_Plane extends Mage_Core_Block_Template
{
    /**
     * Constructor.
     *
     * @copyright Vax Ltd
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('realtimedespatch/imports/view/plane.phtml');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->setChild('xml', $this->getLayout()->createBlock('realtimedespatch/adminhtml_imports_view_accordion'));

        $this->getChild('xml')
            ->setImport($this->getImport());

        $this->getChild('lines')
              ->setImport($this->getImport())
              ->setReferenceLabel($this->getReferenceLabel());

        return parent::_toHtml();
    }
}