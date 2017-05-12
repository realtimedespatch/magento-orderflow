<?php

/**
 * RMA Grid Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Enterprise_Rma_Grid extends Enterprise_Rma_Block_Adminhtml_Rma_Grid
{
    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $result = parent::_prepareCollection();

        Mage::dispatchEvent('adminhtml_enterprise_rma_grid_prepare_collection', array('block' => $this));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $result = parent::_prepareColumns();

        Mage::dispatchEvent('adminhtml_enterprise_rma_grid_prepare_columns', array('block' => $this));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        Mage::dispatchEvent('adminhtml_enterprise_rma_grid_setup_export', array('block' => $this));

        return $this;
    }
}