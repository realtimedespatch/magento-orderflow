<?php

/**
 * Shipment API Endpoint.
 */
class SixBySix_RealTimeDespatch_Model_Sales_Order_Shipment_Api_V2 extends Mage_Api_Model_Resource_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function create(
        $orderIncrementId,
        $skuQty = array(),
        $comment = null,
        $email = false,
        $includeComment = false,
        $courierName = null,
        $serviceName = null,
        $trackingNumber = null,
        $dateShipped = null,
        $messageSeqId = null
    )
    {
        try
        {
            $this->_create(
                $orderIncrementId,
                $skuQty,
                $comment,
                $email,
                $includeComment,
                $courierName,
                $serviceName,
                $trackingNumber,
                $dateShipped,
                $messageSeqId
            );
        }
        catch (Exception $ex) {
            return 'Error Processing Message '.$messageSeqId;
        }

        return 'Message '.$messageSeqId.' Received';
    }

    /**
     * {@inheritdoc}
     */
    public function _create(
        $orderIncrementId,
        $skuQty = array(),
        $comment = null,
        $email = false,
        $includeComment = false,
        $courierName = null,
        $serviceName = null,
        $trackingNumber = null,
        $dateShipped = null,
        $messageSeqId = null
    )
    {
        $tx   = Mage::getModel('core/resource_transaction');
        $body = array(
            'orderIncrementId' => $orderIncrementId,
            'skuQtys'          => $skuQty,
            'comment'          => $comment,
            'email'            => $email,
            'includeComment'   => $includeComment,
            'courierName'      => $courierName,
            'serviceName'      => $serviceName,
            'trackingNumber'   => $trackingNumber,
            'dateShipped'      => $dateShipped,
            'sequenceId'       => $messageSeqId
        );

        $request     = $this->_createRequest($messageSeqId);
        $requestLine = $this->_createRequestLine($request, $body);

        $tx->addObject($request);
        $tx->addObject($requestLine);
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
        $request->setType(SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_SHIPMENT);
        $request->setRequestBody(Mage::getSingleton('core/app')->getRequest()->getRawBody());

        return $request;
    }

    /**
     * Creates a new request line.
     *
     * @param SixBySix_RealTimeDespatch_Model_Request $request
     * @param array                                   $body
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    protected function _createRequestLine($request, $body)
    {
        $requestLine = Mage::getModel('realtimedespatch/request_line');
        $requestLine->setParentRequest($request);
        $requestLine->setType(SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_SHIPMENT);
        $requestLine->setSequenceId($request->getMessageId());
        $requestLine->setBody(json_encode($body));

        return $requestLine;
    }
}