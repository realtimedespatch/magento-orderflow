<?php

/**
 * Export Model.
 */
class SixBySix_RealTimeDespatch_Model_Export extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'realtimedespatch_export';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/export');
    }

    /**
     * Returns the url action for the export.
     *
     * @return string
     */
    public function getUrlAction()
    {
        return lcfirst($this->getEntity()).ucfirst($this->getType()).'s';
    }

    /**
     * Checks whether the export is a success.
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
     * Sets the request body.
     *
     * @param string $requestBody
     *
     * @return SixBySix_RealTimeDespatch_Model_Export
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
     * @return SixBySix_RealTimeDespatch_Model_Export
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
        if ( ! parent::getRequestBody()) {
            return 'Logs Cleaned';
        }

        return gzinflate(parent::getResponseBody());
    }

    /**
     * Updates the entities attached to the export.
     *
     * @return SixBySix_RealTimeDespatch_Model_Export
     */
    public function updateEntities()
    {
        $this->getTypeInstance()->updateEntities($this->getLines());

        return $this;
    }

    /**
     * Returns the lines attached to the export.
     *
     * @return array
     */
    public function getLines()
    {
        return Mage::getModel('realtimedespatch/export_line')
                    ->getCollection()
                    ->addFieldToFilter('export_id', array('eq' => $this->getEntityId()))
                    ->load();
    }

    /**
     * Returns the type instance for the export.
     *
     * @return SixBySix_RealTimeDespatch_Model_Export_Type
     */
    public function getTypeInstance()
    {
        return Mage::getModel('realtimedespatch/export_type_'.lcfirst($this->getEntity()));
    }
}