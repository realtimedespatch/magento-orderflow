<?php

/**
 * Inventory Calculator.
 */
class SixBySix_RealTimeDespatch_Model_Inventory_Calculator
{
    /**
     * Helper.
     *
     * @var mixed
     */
    protected $_helper;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_helper = Mage::helper('realtimedespatch/import_inventory');
    }

    /**
     * Calculates the actual inventory for a product taking into account unsent
     * orders, and active quotes.
     *
     * @param integer  $productId               The ID of the product.
     * @param integer  $unitsReceived           The number of units received from orderfow.
     * @param DateTime $inventoryLastCalculated Timestamp indicating when the inventory was last calculated.
     *
     * @return \Varien_Object
     */
    public function calculate($productId, $unitsReceived, $inventoryLastCalculated)
    {
        $inventory = new Varien_Object;

        $inventory->unitsReceived     = $unitsReceived;
        $inventory->unitsUnsentOrders = $this->_calculateUnsentOrderUnits($productId, $inventoryLastCalculated);
        $inventory->unitsActiveQuotes = $this->_calculateActiveQuoteUnits($productId);

        $inventory->unitsCalculated  = $inventory->unitsReceived;
        $inventory->unitsCalculated -= $inventory->unitsActiveQuotes;
        $inventory->unitsCalculated -= $inventory->unitsUnsentOrders;

        return $inventory;
    }

    /**
     * Calculates the number of units for a product that are yet to be integrated
     * into OrderFlow for it's inventory calculation.
     *
     * This is calculated by summing the number of units attached to unexported orders, and
     * orders that have been exported to OrderFlow after it's inventory calculation.
     *
     * @param integer  $productId
     * @param DateTime $inventoryLastCalculated
     *
     * @return integer
     */
    protected function _calculateUnsentOrderUnits($productId, $inventoryLastCalculated)
    {
        if ( ! $this->_helper->isUnsentOrderAdjustmentEnabled()) {
            return 0;
        }

        // Cutoff Date
        $cutoffDate = $this->_helper->getUnsentOrderCutoffDate();

        // Calculate
        $resource     = Mage::getSingleton('core/resource');
        $conn         = $resource->getConnection('core_read');
        $sfo          = $resource->getTableName('sales_flat_order');
        $sfoi         = $resource->getTableName('sales_flat_order_item');
        $sql          = "SELECT SUM(qty_ordered) AS qty_unsent FROM ".$sfo." AS sfo INNER JOIN ".$sfoi." AS sfoi ON sfo.entity_id = sfoi.order_id WHERE (sfo.status IN ('".implode("','", $this->_helper->getValidUnsentOrderStatuses())."')) AND (sfo.is_virtual = 0) AND (sfo.updated_at > ?) AND ((sfo.is_exported = 0) OR (sfo.exported_at > ?)) AND (sfoi.product_id = ?) GROUP BY sfoi.product_id";
        $unsentUnits  = (integer) $conn->fetchOne($sql, array($cutoffDate, $inventoryLastCalculated->format('Y-m-d H:i:s'), $productId));

        return max(0, $unsentUnits);
    }

    /**
     * Calculates the number of units for a product that are attached to active
     * quotes - these are not taken into account when performing the inventory
     * calculation in OrderFlow.
     *
     * @param integer $productId
     *
     * @return integer
     */
    protected function _calculateActiveQuoteUnits($productId)
    {
        if ( ! $this->_helper->isActiveQuoteAdjustmentEnabled()) {
            return 0;
        }

        // Cutoff Date
        $cutoffDate = $this->_helper->getActiveQuoteCutoffDate();

        // Calculate
        $resource     = Mage::getSingleton('core/resource');
        $conn         = $resource->getConnection('core_read');
        $sfq          = $resource->getTableName('sales_flat_quote');
        $sfqi         = $resource->getTableName('sales_flat_quote_item');
        $sql          = "SELECT SUM(qty) AS qty_active FROM ".$sfq." AS sfq INNER JOIN ".$sfqi." AS sfqi ON sfq.entity_id = sfqi.quote_id WHERE (sfq.is_active = 1) AND (sfqi.product_id = ?) AND (sfq.updated_at > ?) GROUP BY sfqi.product_id";
        $activeUnits  = (integer) $conn->fetchOne($sql, array($productId, $cutoffDate));

        return max(0, $activeUnits);
    }
}