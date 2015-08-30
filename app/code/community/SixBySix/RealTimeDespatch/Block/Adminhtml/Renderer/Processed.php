<?php

/**
 * Processed Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Processed extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        if ( ! $row->getData('processed')) {
            return 'Pending';
        }

        return $row->getData('processed');
    }
}