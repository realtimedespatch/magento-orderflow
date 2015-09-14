<?php

/**
 * Requests View Accordion.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Requests_View_Accordion extends Mage_Adminhtml_Block_Widget_Accordion
{
    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        $this->setId('xml');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->addItem('lastRequest', array(
            'title'   => Mage::helper('customer')->__('Request Body'),
            'ajax'    => false,
            'content' => '<div class="entry-edit"><div style="word-wrap: break-word;" class="fieldset"><pre>'.htmlentities($this->getOrderflowRequest()->getRequestBody()).'</pre></div></div>',
        ));

        return parent::_toHtml();
    }
}