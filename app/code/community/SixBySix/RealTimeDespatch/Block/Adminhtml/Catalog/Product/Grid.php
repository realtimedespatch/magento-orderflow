<?php

/**
 * Product Grid Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();

        Mage::dispatchEvent('adminhtml_catalog_product_grid_setup_export', array('block' => $this));

        return $this;
    }
}