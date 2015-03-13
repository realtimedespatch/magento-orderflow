<?php

/**
 * Process Message ID Renderer.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Process_Message_ID extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function render(Varien_Object $row)
    {
        $id       = $row->getMessageId();
        $executed = $row->getExecuted();

        if ( ! $id && $executed) {
            return __('Pending');
        }

        if ( ! $id & $row->getType() == 'import') {
            $id = SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection::getNextSequencesId($row->getEntity());
        }

        if ( ! $id) {
            $id = __('Pending');
        }

        return $id;
    }
}