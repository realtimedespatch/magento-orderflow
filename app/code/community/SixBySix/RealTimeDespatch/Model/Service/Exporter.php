<?php

use \SixBySix\RealtimeDespatch\Gateway\Exception\AuthenticationException;

/**
 * Abstract Exporter Service.
 */
abstract class SixBySix_RealTimeDespatch_Model_Service_Exporter
{
    /**
     * RTD Service.
     *
     * @var \SixBySix\RealtimeDespatch\Service\AbstractService
     */
    protected $_service;

    /**
     * RTD Service.
     *
     * @param \SixBySix\RealtimeDespatch\Service\AbstractService $service
     *
     * @return void
     */
    public function __construct($service)
    {
        $this->_service = $service;
    }

    /**
     * Performs an export.
     *
     * @param mixed   $entities
     * @param boolean $isManual
     *
     * @return void
     */
    public function export($entities, $isManual = false)
    {
        if ( ! $this->_isEnabled()) {
            return;
        }

        try
        {
            $report = $this->_export($entities);

            $this->_dispatchEvent(
                'rtd_export_success',
                array(
                    'report'       => $report,
                    'requestBody'  => $this->_service->getLastRequestBody(),
                    'responseBody' => $this->_service->getLastResponseBody(),
                    'isManual'     => $isManual,
                )
            );

            return $report;
        }
        catch (Exception $ex)
        {
            $this->_dispatchEvent(
                'rtd_exception',
                array(
                    'exception' => $ex,
                    'entity'    => $this->_getEntity(),
                    'type'      => $this->_getType(),
                )
            );

            Mage::log($ex->getMessage(), null, 'realtimeflow.log');
        }
    }

    /**
     * Returns the appropriate entity mapper.
     *
     * @return mixed
     */
    protected function getMapper()
    {
        return Mage::getModel('realtimedespatch/mapper_'.lcfirst($this->_getEntity()).'s');
    }

    /**
     * Performs an export.
     *
     * @param mixed $entities
     *
     * @return \SixBySix\RealtimeDespatch\Report\ImportReport
     */
    protected abstract function _export($entities);

    /**
     * Checks whether the export is enabled.
     *
     * @return boolean
     */
    protected abstract function _isEnabled();

    /**
     * Returns the entity type of the exporter.
     *
     * @return string
     */
    protected abstract function _getEntity();

    /**
     * Returns the type of the exporter.
     *
     * @return string
     */
    protected abstract function _getType();

    /**
     * Dispatches a new mage event.
     *
     * @param string $name
     * @param array  $params
     *
     * @return void
     */
    protected function _dispatchEvent($name, $params = array())
    {
        Mage::dispatchEvent($name, $params);
    }
}