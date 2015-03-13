<?php

/**
 * Order Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Order_Grid
{
    /**
     * Adds the export column to the orders grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addExportColumn($event)
    {
        $block = $event->getBlock();

        $block->addColumnAfter(
            'is_exported',
            array (
                'header'   => Mage::helper ('realtimedespatch')->__('Exported To OrderFlow'),
                'width'    => '80px',
                'index'    => 'is_exported',
                'type'     => 'options',
                'options'  => array(1 => 'True', 0 => 'False'),
                'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Exported(),
                'align'    => 'center'
            ),
            'status'
        );

        $block->sortColumnsByOrder();
    }

    /**
     * Adds the export action to the orders grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addExportAction($event)
    {
        if ( ! Mage::helper('realtimedespatch/export_order')->isExportEnabled()){
            return;
        }

        $block = $event->getBlock();

        $block->getMassactionBlock()->addItem(
            'export',
            array(
             'label'   => Mage::helper('sales')->__('Export To OrderFlow'),
             'url'     => $block->getUrl('*/*/exportToOrderFlow'),
             'confirm' => Mage::helper('sales')->__('Are you sure?')
       ));
    }
}