<?php

/**
 * Import Lines Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Import_Lines_Grid
{
    /**
     * Adds the order ID column to the import lines grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addOrderIdColumn($event)
    {
        $block = $event->getBlock();

        if ($block->getImport()->getEntity() !== 'Shipment') {
            return;
        }

        $block->addColumnAfter(
            'order_id',
            array (
                'header'   => Mage::helper('realtimedespatch')->__('Order ID'),
                'align'    =>'left',
                'index'    => 'message',
                'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Shipment_Order_Reference(),
            ),
            'order_id'
        );

        $block->sortColumnsByOrder();
    }
}