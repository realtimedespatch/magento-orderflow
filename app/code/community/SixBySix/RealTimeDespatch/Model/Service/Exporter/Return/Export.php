<?php

/**
 * Return Export Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Exporter_Return_Export extends SixBySix_RealTimeDespatch_Model_Service_Exporter
{
    /**
     * {@inheritdoc}
     */
    protected function _isEnabled()
    {
        return Mage::helper('realtimedespatch/export_return')->isExportEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function _getEntity()
    {
        return 'Return';
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
        return $this->_service->importReturns(
            $this->getMapper()->encode(
                $entities,
                $this->_exportedAt
            )
        );
    }
}