<?php

/**
 * Shipment Import Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Importer_Shipment extends SixBySix_RealTimeDespatch_Model_Service_Importer
{
    /**
     * {@inheritdoc}
     */
    protected function _import($requestLines)
    {
        $report      = $this->_createReport();
        $reportLines = array();

        foreach ($requestLines as $requestLine) {
            $reportLines[] = $this->_createShipment($report, $requestLine);
        }

        $report->setLines($reportLines);

        return $report;
    }

    /**
     * Creates a shipment from a request line.
     *
     * @param Varien_Object                                $report
     * @param SixBySix_RealTimeDespatch_Model_Request_Line $requestLine
     *
     * @return Varien_Object
     */
    protected function _createShipment($report, $requestLine)
    {
        $body = $requestLine->getBody();

        try
        {
            if ($this->hasLineBeenPreviouslyProcessed($requestLine->sequence_id)) {
                return $this->_createDuplicateReportLine(
                    $report,
                    $requestLine->sequence_id,
                    'Not Created',
                    'Duplicate Shipment Update Ignored. Sequence ID'
                );
            }

            $shipment = $this->_createShipmentForOrder(
                $this->_retrieveOrder($body->orderIncrementId),
                $body
            );
        }
        catch (Exception $ex)
        {
            return $this->_createFailureReportLine(
                $report,
                $requestLine->sequence_id,
                'Not Created',
                'Order '.$body->orderIncrementId.' not shipped - '.$ex->getMessage()
            );
        }

        return $this->_createSuccessReportLine(
            $report,
            $requestLine->sequence_id,
            $shipment->increment_id,
            'Order '.$body->orderIncrementId.' successfully shipped.'
        );
    }

    /**
     * Creates a shipment from an order.
     *
     * @param Mage_Sales_Model_Order $order
     * @param Varien_Object          $body
     *
     * @return Mage_Sales_Model_Order_Shipment
     */
    protected function _createShipmentForOrder($order, $body)
    {
        $shipment = $order->prepareShipment(
            $this->_prepareItems($order->getId(), $body->skuQtys)
        );

        if ( ! $shipment) {
            throw new Exception('Cannot create shipment.');
        }

        $shipment->register();
        $shipment->addComment($body->comment, $body->email && $body->includeComment);

        if ($body->email) {
            $shipment->setEmailSent(true);
        }

        $shipment->getOrder()->setIsInProcess(true);

        if ($body->trackingNumber) {
            $track = Mage::getModel('sales/order_shipment_track');
            $track->setCarrierCode(Mage_Sales_Model_Order_Shipment_Track::CUSTOM_CARRIER_CODE);
            $track->setTitle($body->courierName.' '.$body->serviceName);
            $track->setNumber($body->trackingNumber);
            $shipment->addTrack($track);
        }

        Mage::getModel('core/resource_transaction')
                    ->addObject($shipment)
                    ->addObject($shipment->getOrder())
                    ->save();

        $shipment->sendEmail($body->email, ($body->includeComment ? $body->comment : ''));

        return $shipment;
    }

    /**
     * Retrieves the current order to ship.
     *
     * @param string $orderIncrementId
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _retrieveOrder($orderIncrementId)
    {
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);

        if ( ! $order->getId()) {
            throw new Exception('Order '.$orderIncrementId.' does not exist.');
        }

        if ( ! $order->canShip()) {
            throw new Exception('Order '.$orderIncrementId.' cannot be shipped.');
        }

        return $order;
    }

    /**
     * Prepares the shipment items
     *
     * @param string $orderId
     * @param array  $skuQtys
     *
     * @return array
     */
    protected function _prepareItems($orderId, $skuQtys)
    {
        $items = array();

        foreach ($skuQtys as $skuQty) {
            $itemIds        = $this->_getItemIdFromSku($orderId, $skuQty->key);
            $itemId         = $itemIds[0]['parent_item_id'] ? $itemIds[0]['parent_item_id'] : $itemIds[0]['item_id'];
            $items[$itemId] = $skuQty->value;
        }

        return $items;
    }

    /**
     * Returns an order item ID via sku.
     *
     * @param string $orderId
     * @param string $sku
     *
     * @return string
     */
    protected function _getItemIdFromSku($orderId, $sku){
        $resource = Mage::getSingleton('core/resource');
        $conn     = $resource->getConnection('core_read');
        $sfoi     = $resource->getTableName('sales_flat_order_item');
        $sql      = "SELECT item_id, parent_item_id FROM ".$sfoi." WHERE order_id = ? and sku = ?";

        return $conn->fetchAll($sql, array($orderId, $sku));
    }

    /**
     * {@inheritdoc}
     */
    protected function _isEnabled()
    {
        return Mage::helper('realtimedespatch/import_shipment')->isImportEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function _getEntity()
    {
        return 'Shipment';
    }
}