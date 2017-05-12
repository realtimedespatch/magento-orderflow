<?php

/**
 * Return Import Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Importer_Return extends SixBySix_RealTimeDespatch_Model_Service_Importer
{
    const ENTITY_RETURN = 'Return';

    /**
     * @var Enterprise_Rma_Model_Rma
     */
    protected $_return;

    /**
     * {@inheritdoc}
     */
    protected function _import($request)
    {
        $report      = $this->_createReport($request);
        $reportLines = array();

        foreach ($request->getLines() as $requestLine) {
            $reportLines[] = $this->_processReturnItem($report, $requestLine);
        }

        $report->setLines($reportLines);

        return $report;
    }

    /**
     * Processes an individual return item.
     *
     * @param $report
     * @param $requestLine
     *
     * @return mixed
     */
    protected function _processReturnItem($report, $requestLine)
    {
        $body = $requestLine->getBody();
        $returnReference = $body->returnReference;
        $isApproved = (boolean) $body->processRefund;

        try {
            $return = $this->_retrieveReturn($returnReference);
            $returnItem = $this->_retrieveReturnItem($return, $body->product);

            // Check authorized quantity.
            if ($body->quantity > ($returnItem->getQtyAuthorized() - $returnItem->getQtyReturned())) {
                $totalReceived = $returnItem->getQtyReturned() + $body->quantity;
                throw new Exception('The total quantity received ('.$totalReceived.') is greater than the quantity authorised ('.(integer) $returnItem->getQtyAuthorized().') for SKU '.$body->product);
            }

            // Update the return item status.
            if ($isApproved) {
                $comment = $this->_approveReturnItem($return, $returnItem, $body);
            } else {
                $comment = $this->_rejectReturnItem($return, $returnItem, $body);
            }

            return $this->_createSuccessReportLine(
                $report,
                $requestLine->sequence_id,
                $body->returnReference,
                $comment
            );
        }
        catch (Exception $ex) {
            return $this->_createFailureReportLine(
                $report,
                $requestLine->sequence_id,
                $returnReference,
                $ex->getMessage()
            );
        }
    }

    /**
     * Approves the return item
     *
     * @param $return
     * @param $returnItem
     * @param $details
     *
     * @return string
     */
    protected function _approveReturnItem($return, $returnItem, $body)
    {
        $returnItem->setQtyReturned($body->quantity + $returnItem->getQtyReturned());
        $returnItem->setQtyApproved($body->quantity + $returnItem->getQtyApproved());
        $returnItem->setStatus(Enterprise_Rma_Model_Rma_Source_Status::STATE_APPROVED);
        $returnItem->save();

        $this->_updateReturnStatus($return);

        $comment = 'Approved quantity ('.$body->quantity.') for SKU '.$body->product.' received in warehouse. Condition: '.$body->condition.'. Note: '.$body->note;
        $return->addComment($comment);

        return $comment;
    }

    /**
     * Rejects the return item.
     *
     * @param $return
     * @param $returnItem
     * @param $details
     *
     * @return string
     */
    protected function _rejectReturnItem($return, $returnItem, $body)
    {
        $returnItem->setQtyReturned($body->quantity + $returnItem->getQtyReturned());
        $returnItem->setQtyApproved(max(array($returnItem->getQtyApproved(), 0)));

        // We do not set the line to rejected if items have already been approved.
        if ($returnItem->getStatus() !== Enterprise_Rma_Model_Rma_Source_Status::STATE_APPROVED) {
            $returnItem->setStatus(Enterprise_Rma_Model_Rma_Source_Status::STATE_REJECTED);
        }

        $returnItem->save();

        $this->_updateReturnStatus($return);

        $comment = 'Rejected quantity ('.$body->quantity.') for SKU '.$body->product.' received in warehouse. Condition: '.$body->condition.'. Note: '.$body->note;
        $return->addComment($comment);

        return $comment;
    }

    /**
     * Updates the status of the return based on the latest line update.
     *
     * See: Enterprise_Rma_Adminhtml_RmaController
     *
     * @param $return
     *
     * @return void
     */
    protected function _updateReturnStatus($return)
    {
        $returnItems = Mage::getModel('enterprise_rma/item')
            ->getCollection()
            ->addAttributeToFilter('rma_entity_id', $return->getId());

        foreach ($returnItems as $returnItem) {
            $statuses[] = $returnItem->getStatus();
        }

        $return->setStatus(
            Mage::getModel('enterprise_rma/rma_source_status')->getStatusByItems($statuses)
        );

        $return->save();
    }

    /**
     * Retrieves a return by increment ID.
     *
     * @param string $returnReference
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _retrieveReturn($returnReference)
    {
        if (  ! $this->_return) {
            $this->_return = Mage::getModel('enterprise_rma/rma')->load($returnReference, 'increment_id');
        }

        if ( ! $this->_return->getId()) {
            throw new Exception('Cannot find Return #'.$returnReference);
        }

	Mage::log($this->_return->getId(), null, 'tom.log');

        return $this->_return;
    }

    /**
     * Retrieves a return item.
     *
     * @param string $return
     * @param string $sku
     *
     * @return Mage_Sales_Model_Order
     */
    protected function _retrieveReturnItem($return, $sku)
    {
        $item = Mage::getModel('enterprise_rma/item')
            ->getCollection()
            ->addFieldToFilter('rma_entity_id', $return->getId())
            ->addFieldToFilter('product_sku', $sku)
            ->getFirstItem();

        if ( ! $item->getId()) {
            throw new Exception('Cannot find authorized return item for Product SKU ' . $sku);
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isEnabled()
    {
        return Mage::helper('realtimedespatch/import_return')->isImportEnabled();
    }

    /**
     * {@inheritdoc}
     */
    protected function _getEntity()
    {
        return self::ENTITY_RETURN;
    }
}
