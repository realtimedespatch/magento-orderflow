<?php

/**
 * Inventory Import Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Importer_Inventory extends SixBySix_RealTimeDespatch_Model_Service_Importer
{
    /**
     * {@inheritdoc}
     */
    protected function _import($requestLines)
    {
        $write       = Mage::getSingleton('core/resource')->getConnection('core_write');
        $report      = $this->_createReport();
        $reportLines = array();

        foreach ($requestLines as $requestLine) {
            $body      = $requestLine->getBody();
            $sku       = $body->key;
            $productId = Mage::getModel("catalog/product")->getIdBySku($body->key);

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

            try
            {
                $this->_updateInventory(
                    $write,
                    array(
                        $body->value,
                        $body->value > 0 ? 1 : 0,
                        $body->value,
                        $body->value > 0 ? 1 : 0,
                        $productId,
                    )
                );

                // ensure product update was successful
                $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productId);

                if (!$stock->getId()) {
                    throw new Exception(
                        sprintf(
                            "Product Quantity could not be updated to '%d', no stock record found",
                            (int) $body->value
                        )
                    );
                }

                if ($stock->getQty() != $body->value) {
                    throw new Exception(
                        sprintf(
                            "Product Quantity could not be updated to '%d'",
                            (int) $body->value
                        )
                    );
                }

                $reportLines[] = $this->_createSuccessReportLine(
                    $report,
                    $requestLine->sequence_id,
                    $sku,
                    'Product Quantity Successfully Updated to '.$body->value
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
        
        // Only fire pre-instantiated objects to reduce overhead.
        $productArray = array('product_id' => $productId,
	                          'stock' => $stock);

	    Mage::dispatchEvent('catalog_product_stock_save_after', $productArray);

        $report->setLines($reportLines);

        return $report;
    }

    /**
     * Updates an individual products inventory.
     *
     * @param mixed $write
     * @param array $binds
     *
     * @return void
     */
    protected function _updateInventory($write, $binds)
    {
        $rsc = Mage::getSingleton("core/resource");
        $csi = $rsc->getTableName('cataloginventory_stock_item');
        $css = $rsc->getTableName('cataloginventory_stock_status');

        $sql = "UPDATE ".$csi." csi, ".$css." css
                SET
                csi.qty = ?,
                csi.is_in_stock = ?,
                css.qty = ?,
                css.stock_status = ?
                WHERE
                csi.product_id = ?
                AND csi.product_id = css.product_id";

        $write->query($sql, $binds);
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
