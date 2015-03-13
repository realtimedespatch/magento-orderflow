<?php

/**
 * Exports View Details Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Exports_View_Plane extends Mage_Core_Block_Template
{
    /**
     * Constructor.
     *
     * @copyright Vax Ltd
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('realtimedespatch/exports/view/plane.phtml');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->setChild('xml', $this->getLayout()->createBlock('realtimedespatch/adminhtml_exports_view_accordion'));

        $this->getChild('xml')
            ->setExport($this->getExport());

        $this->getChild('lines')
              ->setExport($this->getExport())
              ->setReferenceLabel($this->getReferenceLabel());

        return parent::_toHtml();
    }
}