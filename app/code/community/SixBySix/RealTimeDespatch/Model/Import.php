<?php

/**
 * Import Model.
 */
class SixBySix_RealTimeDespatch_Model_Import extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'realtimedespatch_import';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/import');
    }

    /**
     * Sets the request body.
     *
     * @param string $requestBody
     *
     * @return SixBySix_RealTimeDespatch_Model_Import
     */
    public function setRequestBody($requestBody)
    {
        $this->setMessageIdFromRequestBody($requestBody);

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
            return 'Request Unavailable';
        }

        $xml = '';

        try
        {
            $dom = new \DOMDocument;
            $dom->preserveWhiteSpace = false;
            $dom->loadXML(gzinflate(parent::getRequestBody()));
            $dom->formatOutput = true;
            $xml = $dom->saveXml();
        }
        catch (\Exception $ex)
        {
            $xml = 'Request Unavailable';
        }

        return $xml;
    }

    /**
     * Sets the response body.
     *
     * @param string $responseBody
     *
     * @return SixBySix_RealTimeDespatch_Model_Import
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
            return 'Response Unavailable';
        }

        return gzinflate(parent::getResponseBody());
    }

    /**
     * Checks whether the import is a success.
     *
     * @return boolean
     */
    public function isSuccess()
    {
        if ($this->getDuplicates() > 0) {
            return false;
        }

        if ($this->getFailures() > 0) {
            return false;
        }

        return true;
    }

    /**
     * Returns the lines attached to the import.
     *
     * @return array
     */
    public function getLines()
    {
        return Mage::getModel('realtimedespatch/import_line')
                    ->getCollection()
                    ->addFieldToFilter('import_id', array('eq' => $this->getEntityId()))
                    ->load();
    }

    /**
     * Message ID Getter.
     *
     * @return string
     */
    public function getMessageId()
    {
        if ( ! parent::getMessageId()) {
            return 'Unavailable';
        }

        return parent::getMessageId();
    }

    /**
     * Returns the import message ID.
     *
     * @param string $requestBody
     *
     * @return string
     */
    public function setMessageIdFromRequestBody($requestBody = null)
    {
        if ( ! $requestBody) {
            return;
        }

        try
        {
            $dom = new \DOMDocument;
            $dom->loadXML($requestBody);
            $xml = $dom->getElementsByTagName('messageSeqId');
            $xml = $xml->item(0)->nodeValue;
            $this->setMessageId($xml);
        }
        catch (\Exception $ex)
        {
            $xml = 'Unavailable';
        }
    }

    /**
     * Returns the admin url to the import.
     *
     * @return string
     */
    public function getAdminUrl()
    {
        if ( ! $this->getId()) {
            return '';
        }

        return Mage::helper('adminhtml')->getUrl('adminhtml/inventoryImports/view/id/'.$this->getId());
    }
}