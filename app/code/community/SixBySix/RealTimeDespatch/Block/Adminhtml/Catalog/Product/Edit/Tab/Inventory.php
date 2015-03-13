<?php

/**
 * Product Inventory Tab Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Catalog_Product_Edit_Tab_Inventory extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Inventory
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setTemplate('realtimedespatch/catalog/product/tab/inventory.phtml');
    }
}