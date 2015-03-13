<?php

/**
 * Sales Order Grid Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $result = parent::_prepareCollection();

        Mage::dispatchEvent('adminhtml_sales_order_grid_prepare_collection', array('block' => $this));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $result = parent::_prepareColumns();

        Mage::dispatchEvent('adminhtml_sales_order_grid_prepare_columns', array('block' => $this));

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        Mage::dispatchEvent('adminhtml_sales_order_grid_prepare_massaction', array('block' => $this));

        return $this;
    }
}