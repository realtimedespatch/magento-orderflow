<?php

/**
 * Catalog Inventory API Endpoint.
 */
class SixBySix_RealTimeDespatch_Model_Inventory_Stock_Item_Api_V2 extends Mage_Api_Model_Resource_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function multiUpdate($productQtys, $productSeqs, $messageSeqId)
    {
        try
        {
            $this->_multiUpdate($productQtys, $productSeqs, $messageSeqId);
        }
        catch (Exception $ex) {
            return 'Error Processing Message '.$messageSeqId;
        }

        return 'Success - Message '.$messageSeqId.' Received';
    }

    /**
     * {@inheritdoc}
     */
    protected function _multiUpdate($productQtys, $productSeqs, $messageSeqId)
    {
        $tx      = Mage::getModel('core/resource_transaction');
        $request = $this->_createRequest($messageSeqId);

        $tx->addObject($request);

        foreach ($productSeqs as $index => $seq) {
            $body = $productQtys[$index];

            if (isset($seq->lastOrderExported) && $seq->lastOrderExported) {
                $body->lastOrderExported = $seq->lastOrderExported;
            }

            $requestLine = $this->_createRequestLine(
                $request,
                $seq->value,
                json_encode($body)
            );

            $tx->addObject($requestLine);
        }

        $tx->save();
    }

    /**
     * Creates a new request.
     *
     * @param string $messageSeqId
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    protected function _createRequest($messageSeqId)
    {
        $request = Mage::getModel('realtimedespatch/request');
        $request->setMessageId($messageSeqId);
        $request->setType(SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_INVENTORY);
        $request->setRequestBody(Mage::getSingleton('core/app')->getRequest()->getRawBody());

        return $request;
    }

    /**
     * Creates a new request line.
     *
     * @param SixBySix_RealTimeDespatch_Model_Request $request
     * @param string                                  $sequenceId
     * @param string                                  $body
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    protected function _createRequestLine($request, $sequenceId, $body)
    {
        $requestLine = Mage::getModel('realtimedespatch/request_line');
        $requestLine->setParentRequest($request);
        $requestLine->setType(SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_INVENTORY);
        $requestLine->setSequenceId($sequenceId);
        $requestLine->setBody($body);

        return $requestLine;
    }
}