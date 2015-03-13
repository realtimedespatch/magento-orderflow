<?php

/**
 * Shipment Order Reference Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Shipment_Order_Reference extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        $tokens = explode(' ', $row->getData('message'));

        return $tokens[1];
    }
}