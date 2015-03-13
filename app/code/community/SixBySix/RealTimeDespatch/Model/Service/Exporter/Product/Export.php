<?php

/**
 * Product Export Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Exporter_Product_Export extends SixBySix_RealTimeDespatch_Model_Service_Exporter
{
    /**
     * {@inheritdoc}
     */
    protected function _isEnabled()
    {
        return Mage::helper('realtimedespatch/export_product')->isExportEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function _getEntity()
    {
        return 'Product';
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
        return $this->_service->mergeProducts(
            $this->getMapper()->encode($entities)
        );
    }
}