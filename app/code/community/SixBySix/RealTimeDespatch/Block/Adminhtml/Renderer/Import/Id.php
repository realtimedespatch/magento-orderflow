<?php

/**
 * Import ID Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Import_Id extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        if ( ! $row->getProcessed()) {
            return 'Pending';
        }

        $import = Mage::getModel('realtimedespatch/import')->load($row->getMessageId(), 'message_id');

        if ( ! $import->getId()) {
            return 'Pending';
        }

        return '<a href="'.$import->getAdminUrl().'" title="View Import">'.$import->getId().'</a>';
    }
}