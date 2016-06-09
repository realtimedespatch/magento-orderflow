<?php

/**
 * Import Lines Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Import_Lines_Grid
{
    /**
     * Adds additional grid columns.
     *
     * @param type $event
     */
    public function addAdditionalColumns($event)
    {
        $this->addAdjustmentColumns($event);
        $this->addOrderIdColumn($event);
    }

    /**
     * Adds the inventry adjustment columns to the import lines grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addAdjustmentColumns($event)
    {
        $block = $event->getBlock();

        if ($block->getImport()->getEntity() !== 'Inventory') {
            return;
        }

        $block->addColumnAfter(
            'received_units',
            array (
                'header'   => Mage::helper('realtimedespatch')->__('Units Received'),
                'align'    =>'left',
                'index'    => 'reference',
                 'filter'  => false,
                'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Inventory_Received(),
            ),
            'reference'
        );

        $block->addColumnAfter(
            'quote_adjustments',
            array (
                'header'   => Mage::helper('realtimedespatch')->__('Units Adjusted (Quotes)'),
                'align'    =>'left',
                'index'    => 'reference',
                 'filter'  => false,
                'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Inventory_Adjustments_Quote,
            ),
            'received_units'
        );

        $block->addColumnAfter(
            'order_adjustments',
            array (
                'header'   => Mage::helper('realtimedespatch')->__('Units Adjusted (Queued)'),
                'align'    =>'left',
                'index'    => 'reference',
                 'filter'  => false,
                'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Inventory_Adjustments_Order(),
            ),
            'quote_adjustments'
        );

        $block->addColumnAfter(
            'updated_units',
            array (
                'header'   => Mage::helper('realtimedespatch')->__('Updated Stock Qty'),
                'align'    =>'left',
                'index'    => 'reference',
                 'filter'  => false,
                'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Inventory_Updated(),
            ),
            'order_adjustments'
        );

        $block->sortColumnsByOrder();
    }

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