<?php

/**
 * Order Export Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Exporter_Order_Export extends SixBySix_RealTimeDespatch_Model_Service_Exporter
{
    /**
     * {@inheritdoc}
     */
    protected function _isEnabled()
    {
        return Mage::helper('realtimedespatch/export_order')->isExportEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function _getEntity()
    {
        return 'Order';
    }

    /**
     * {@inheritdoc}
     */
    protected function _getType()
    {
        return 'Export';
    }

    /**
     * {@inheritdoc}
     */
    public function _export($entities)
    {
        return $this->_service->importOrders(
            $this->getMapper()->encode($entities)
        );
    }
}