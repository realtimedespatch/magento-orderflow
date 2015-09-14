<?php

/**
 * Bulk Export View Plane.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Bulk_Export_View_Plane extends Mage_Core_Block_Template
{
    /**
     * Constructor.
     *
     * @copyright Vax Ltd
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('realtimedespatch/bulk/export/view/plane.phtml');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        return parent::_toHtml();
    }
}