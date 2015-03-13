<?php

/**
 * Exported Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Exported extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        if ( ! $row->getData('is_exported')) {
            return 'False';
        }

        return 'True';
    }
}