<?php

/**
 * Total Imports Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Total_Imports extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        $total  = $row->getData('successes');
        $total += $row->getData('duplicates');
        $total += $row->getData('failures');

        return $total;
    }
}