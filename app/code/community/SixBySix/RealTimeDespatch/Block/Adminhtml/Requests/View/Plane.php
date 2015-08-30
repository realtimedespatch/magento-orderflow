<?php

/**
 * Requests View Details Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Requests_View_Plane extends Mage_Core_Block_Template
{
    /**
     * Constructor.
     *
     * @copyright Vax Ltd
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('realtimedespatch/requests/view/plane.phtml');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->setChild('xml', $this->getLayout()->createBlock('realtimedespatch/adminhtml_requests_view_accordion'));

        $this->getChild('xml')
            ->setOrderflowRequest($this->getOrderflowRequest());

        $this->getChild('lines')
              ->setOrderflowRequest($this->getOrderflowRequest());

        return parent::_toHtml();
    }
}