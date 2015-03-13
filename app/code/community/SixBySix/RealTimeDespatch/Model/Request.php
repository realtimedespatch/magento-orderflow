<?php

/**
 * Request Model.
 */
class SixBySix_RealTimeDespatch_Model_Request extends Mage_Core_Model_Abstract
{
    /**
     * Model Event Prefix.
     *
     * @var string
     */
    protected $_eventPrefix = 'realtimedespatch_request';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/request');
    }

    /**
     * Sets the request body.
     *
     * @param string $requestBody
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    public function setRequestBody($requestBody)
    {
        return parent::setRequestBody(gzdeflate($requestBody, 9));
    }

    /**
     * Returns the request body.
     *
     * @return string
     */
    public function getRequestBody()
    {
        if ( ! parent::getRequestBody()) {
            return 'Logs Cleaned';
        }

        return gzinflate(parent::getRequestBody());
    }

    /**
     * Sets the response body.
     *
     * @param string $responseBody
     *
     * @return SixBySix_RealTimeDespatch_Model_Request
     */
    public function setResponseBody($responseBody)
    {
        return parent::setResponseBody(gzdeflate($responseBody, 9));
    }

    /**
     * Returns the response body.
     *
     * @return string
     */
    public function getResponseBody()
    {
        if ( ! parent::getResponseBody()) {
            return 'Logs Cleaned';
        }

        return gzinflate(parent::getResponseBody());
    }
}