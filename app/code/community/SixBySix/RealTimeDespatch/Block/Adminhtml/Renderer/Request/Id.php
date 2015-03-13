<?php

/**
 * Request Id.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Request_Id extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        if ( ! $row->getData('message_id')) {
            return 'Unavailable';
        }

        return $row->getData('message_id');
    }
}