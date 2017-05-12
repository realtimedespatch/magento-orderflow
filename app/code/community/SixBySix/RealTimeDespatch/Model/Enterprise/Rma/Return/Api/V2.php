<?php

/**
 * Enterprise Return API Endpoint.
 */
class SixBySix_RealTimeDespatch_Model_Enterprise_Rma_Return_Api_V2 extends Mage_Api_Model_Resource_Abstract
{
    /**
     * {@inheritdoc}
     */
    public function update($authorisation, $orderReference, $items)
    {
        try {
            $this->_update($authorisation, $orderReference, $items);
        }
        catch (Exception $ex) {
            return 'Error Processing Message '.$authorisation;
        }

        return 'Success - Return '.$authorisation.' Received';
    }

    /**
     * {@inheritdoc}
     */
    protected function _update($authorisation, $orderReference, $items)
    {
        $tx = Mage::getModel('core/resource_transaction');
        $request = $this->_createRequest($authorisation, $orderReference);

        $tx->addObject($request);

        $i = 1;

        foreach ($items as $item) {
            $requestLine = $this->_createRequestLine($request, $item, $i);
            $tx->addObject($requestLine);
            $i++;
        }

        $tx->save();
    }

    /**
     * Creates a new request.
     *
     * @param string $authorisation
     * @param string $orderReference
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    protected function _createRequest($authorisation, $orderReference)
    {
        $request = Mage::getModel('realtimedespatch/request');
        $request->setMessageId(Mage::helper('realtimedespatch/import_return')->getNextMessageId());
        $request->setReturnReference($authorisation);
        $request->setOrderReference($orderReference);
        $request->setType(SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_RETURN);
        $request->setRequestBody(Mage::getSingleton('core/app')->getRequest()->getRawBody());

        return $request;
    }

    /**
     * Creates a new request line.
     *
     * @param SixBySix_RealTimeDespatch_Model_Request $request
     * @parma array                                   $item
     * @parma integer                                 $sequenceId
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    protected function _createRequestLine($request, $item, $sequenceId)
    {
        $requestLine = Mage::getModel('realtimedespatch/request_line');
        $requestLine->setParentRequest($request);
        $requestLine->setType(SixBySix_RealTimeDespatch_Model_Request_Type::REQUEST_TYPE_RETURN);
        $requestLine->setSequenceId($sequenceId);
        $item->returnReference = $request->getReturnReference();
        $item->orderReference = $request->getOrderReference();
        $requestLine->setBody(json_encode($item));

        return $requestLine;
    }
}
