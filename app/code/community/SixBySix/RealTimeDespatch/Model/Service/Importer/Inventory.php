<?php

/**
 * Inventory Import Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Importer_Inventory extends SixBySix_RealTimeDespatch_Model_Service_Importer
{
    /**
     * {@inheritdoc}
     */
    protected function _import($request)
    {
        $report      = $this->_createReport($request);
        $reportLines = array();

        foreach ($request->getLines() as $requestLine) {
            $body              = $requestLine->getBody();
            $sku               = $body->key;
            $unitsReceived     = (int) $body->value;
            $productId         = Mage::getModel("catalog/product")->getIdBySku($body->key);
            $lastOrderExported = isset($body->lastOrderExported) ? new DateTime($body->lastOrderExported) : new DateTime;

            if ( ! $productId) {
                $reportLines[] = $this->_createFailureReportLine(
                    $report,
                    $requestLine->sequence_id,
                    $sku,
                    'SKU Does Not Exist'
                );
                continue;
            }

            if ($this->hasLineBeenPreviouslyProcessed($requestLine->sequence_id)) {
                $reportLines[] = $this->_createDuplicateReportLine(
                    $report,
                    $requestLine->sequence_id,
                    $sku,
                    'Duplicate Inventory Update Ignored.'
                );
                continue;
            }

            if ($importLine = $this->getSupersedingImportLine($sku, $requestLine->sequence_id)) {
                $reportLines[] = $this->_createDuplicateReportLine(
                    $report,
                    $requestLine->sequence_id,
                    $sku,
                    sprintf(
                        'Product quantity update to %d discarded as already superseded by inventory record %d',
                        $unitsReceived,
                        $importLine->getSequenceId()
                    )
                );
                continue;
            }

            try
            {
                $inventory = $this->_calculateInventory(
                    $productId,
                    $unitsReceived,
                    $lastOrderExported
                );

                $this->_updateInventory(
                    $inventory->unitsCalculated,
                    $productId
                );

                // ensure product update was successful
                $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);

                if ( ! $stock->getId()) {
                    throw new Exception(
                        sprintf(
                            "Product Quantity could not be updated to '%d', no stock record found",
                            $inventory->unitsCalculated
                        )
                    );
                }

                if ($stock->getQty() != $inventory->unitsCalculated) {
                    throw new Exception(
                        sprintf(
                            "Product Quantity could not be updated to '%d'",
                            $inventory->unitsCalculated
                        )
                    );
                }

                $report->additionalData = $inventory->toArray();

                $reportLines[] = $this->_createSuccessReportLine(
                    $report,
                    $requestLine->sequence_id,
                    $sku,
                    'Product Quantity Successfully Updated to '.$inventory->unitsCalculated
                );
            }
            catch (Exception $ex)
            {
                $reportLines[] = $this->_createFailureReportLine(
                    $report,
                    $requestLine->sequence_id,
                    $sku,
                    $ex->getMessage()
                );
            }
        }

        $report->setLines($reportLines);

        return $report;
    }

    /**
     * Calculates the values for the inventory update.
     *
     * @param integer  $productId
     * @parem integer  $unitsReceived
     * @param DateTime $lastOrderExported
     *
     * @return Varien_Object
     */
    protected function _calculateInventory($productId, $unitsReceived, $lastOrderExported)
    {
        return Mage::getModel('realtimedespatch/inventory_calculator')->calculate(
            $productId,
            $unitsReceived,
            $lastOrderExported
        );
    }

    /**
     * Prevent an import from being performed if inventory updates have
     * fallen out of sync.
     *
     * @param string $sku
     * @param int $sequenceId
     *
     * @return SixBySix_RealTimeDespatch_Model_Import_Line
     */
    public function getSupersedingImportLine($sku, $sequenceId)
    {
        /** @var SixBySix_RealTimeDespatch_Model_Import_Line $importLine */
        $importLine = SixBySix_RealTimeDespatch_Model_Resource_Import_Line_Collection::getLatestImportLineByReference(
            $this->_getEntity(),
            $sku
        );

        if ($importLine && $importLine->getSequenceId() > $sequenceId) {
            return $importLine;
        }
    }

    /**
     * Updates an individual product's inventory.
     *
     * @param mixed $qty
     * @param array $productId
     *
     * @return void
     */
    protected function _updateInventory($qty, $productId)
    {
        $helper      = Mage::helper('realtimedespatch/import_inventory');
        $isInStock   = $qty > 0 ? 1 : 0;
        $stockStatus = $isInStock;

        if ( ! $helper->isNegativeQtyEnabled() && $qty < 0) {
            $qty = 0;
        }

        $this->_writeInventory(
            array(
                $qty,
                $isInStock,
                $productId
            ),
        	array(
                $qty,
                $stockStatus,
                $productId
            )
        );
    }

    /**
     * Updates an individual product's inventory.
     *
     * @param array $binds
     *
     * @return void
     */
    protected function _writeInventory($itemBinds,$statusBinds)
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $rsc   = Mage::getSingleton("core/resource");
        $csi   = $rsc->getTableName('cataloginventory_stock_item');
        $css   = $rsc->getTableName('cataloginventory_stock_status');

        $itemSql = "UPDATE ".$csi." csi
                SET
                csi.qty = ?,
                csi.is_in_stock = ?
                WHERE
                csi.product_id = ?";

        $write->query($itemSql, $itemBinds);

        $statusSql = "UPDATE ".$css." css
                SET
                css.qty = ?,
                css.stock_status = ?
                WHERE
                css.product_id = ?";

        $write->query($statusSql, $statusBinds);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isEnabled()
    {
        return Mage::helper('realtimedespatch/import_inventory')->isImportEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function _getEntity()
    {
        return 'Inventory';
    }
}