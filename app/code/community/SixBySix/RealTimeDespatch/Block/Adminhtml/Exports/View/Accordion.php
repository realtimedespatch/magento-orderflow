<?php

/**
 * Exports View Accordion.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Exports_View_Accordion extends Mage_Adminhtml_Block_Widget_Accordion
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
            'title'   => Mage::helper('customer')->__('Request XML'),
            'ajax'    => false,
            'content' => '<div class="entry-edit"><div style="word-wrap: break-word;" class="fieldset"><pre>'.htmlentities($this->getExport()->getRequestBody()).'</pre></div></div>',
        ));

        $this->addItem('lastResponse', array(
            'title'   => Mage::helper('customer')->__('Response XML'),
            'ajax'    => false,
            'content' => '<div class="entry-edit"><div  style="word-wrap: break-word;" class="fieldset"><pre>'.htmlentities($this->getExport()->getResponseBody()).'</pre></div></div>',
        ));

        return parent::_toHtml();
    }
}