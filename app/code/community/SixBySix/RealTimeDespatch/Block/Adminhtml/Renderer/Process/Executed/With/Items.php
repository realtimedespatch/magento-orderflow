<?php

/**
 * Process Executed With Items Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Process_Executed_With_Items extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        $executed = $row->getExecutedWithItems();

        if ($executed) {
            return $executed;
        }

        $executed = SixBySix_RealTimeDespatch_Model_Resource_Process_Schedule_Collection::getLastScheduleWithItems(
            $row->getEntity()
        )->getExecutedWithItems();

        if ($executed) {
            return $executed;
        }

        return 'Pending';
    }
}