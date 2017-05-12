<?php

/**
 * Rma Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Rma_Grid
{
    /**
     * Adds the export column to the rma grid.
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
     * Adds an export button to the RMA view.
     *
     * @param $event
     */
    public function addExportButton($event)
    {
        if ( ! Mage::helper('realtimedespatch/export_return')->isExportEnabled()){
            return;
        }

        $block = $event->getBlock();

        if ( ! ($block instanceof Enterprise_Rma_Block_Adminhtml_Rma_Edit)) {
            return;
        }

        $block->addButton('export_button',
            array(
                'label'   => Mage::helper('realtimedespatch')->__('Export'),
                'onclick' => 'setLocation(\''.$block->getUrl('*/*/exportToOrderFlow', array('rma_id' => $block->getRma()->getId())).'\')',
                'class'   => 'save',
                'confirm' => Mage::helper('realtimedespatch')->__('Are you sure?')
            ),
            100
        );
    }
}