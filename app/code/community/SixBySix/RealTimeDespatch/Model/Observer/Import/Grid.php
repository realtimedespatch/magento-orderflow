<?php

/**
 * Import Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Import_Grid
{
    /**
     * Adds the order ID column to the imports grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addOrderIdColumn($event)
    {
        $block = $event->getBlock();

        if ($block->getEntityType() !== 'Shipment') {
            return;
        }

        $block->addColumnAfter(
            'order_id',
            array (
                'header'   => Mage::helper('realtimedespatch')->__('Unique Order IDs'),
                'align'    =>'left',
                'index'    => 'message',
                'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Total_Imports(),
            ),
            'message_id'
        );

        $block->sortColumnsByOrder();
    }
}