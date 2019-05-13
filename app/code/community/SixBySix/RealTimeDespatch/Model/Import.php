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
     * Returns the request body.
     *
     * @return string
     */
    public function getRequestBody()
    {
        return Mage::getModel('realtimedespatch/request')
            ->load($this->getRequestId())
            ->getRequestBody();
    }

    /**
     * Returns the response body.
     *
     * @return string
     */
    public function getResponseBody()
    {
        return Mage::getModel('realtimedespatch/request')
            ->load($this->getRequestId())
            ->getResponseBody();
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
        return Mage::getModel('realtimedespatch/request')
            ->load($this->getRequestId())
            ->getMessageId();
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

        return Mage::helper('adminhtml')->getUrl('adminhtml/'.strtolower($this->getEntity()).'Imports/view/id/'.$this->getId());
    }
}