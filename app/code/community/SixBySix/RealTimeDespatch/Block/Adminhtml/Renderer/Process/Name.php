<?php

/**
 * Process Name Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Process_Name extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        return ucfirst($row->getData('type')).' '.ucfirst($row->getData('entity'));
    }
}