<?php

/**
 * Order Item Model.
 */
class SixBySix_RealTimeDespatch_Model_Sales_Order_Item extends Mage_Sales_Model_Order_Item
{
    /**
     * Checks whether this is a simple order line.
     *
     * @return boolean
     */
    public function isSimple()
    {
        return $this->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_SIMPLE;
    }

    /**
     * Checks whether this is a bundled order line.
     *
     * @return boolean
     */
    public function isBundle()
    {
        return $this->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_BUNDLE;
    }

    /**
     * Checks whether this is a grouped order line.
     *
     * @return boolean
     */
    public function isGrouped()
    {
        return $this->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_GROUPED;
    }

    /**
     * Checks whether this is a virtual order line.
     *
     * @return boolean
     */
    public function isVirtual()
    {
        return $this->getProductType() == Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL;
    }

    /**
     * Returns the unit tax amount.
     *
     * @return float
     */
    public function getUnitTaxAmount()
    {
        return number_format($this->getTaxAmount() / $this->getQtyOrdered(), 4);
    }
}