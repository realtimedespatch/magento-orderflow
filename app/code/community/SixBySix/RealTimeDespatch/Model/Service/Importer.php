<?php

/**
 * Abstract Importer Service.
 */
abstract class SixBySix_RealTimeDespatch_Model_Service_Importer
{
    /**
     * Success IDs.
     *
     * @var array
     */
    protected $_processedIds = array();

    /**
     * Performs an import.
     *
     * @param mixed $request
     *
     * @return void
     */
    public function import($request)
    {
        if ( ! $this->_isEnabled()) {
            return;
        }

        try
        {
            $report = $this->_import($request);

            $this->_dispatchEvent(
                'rtd_import_success',
                array(
                    'report' => $report,
                    'lines'  => $request->getLines(),
                    'entity' => $this->_getEntity(),
                    'type'   => $this->_getType(),
                )
            );
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
     * Creates a new report.
     *
     * @param mixed $request
     *
     * @return Varien_Object
     */
    protected function _createReport($request)
    {
        $report = new Varien_Object;

        $report->setType($this->_getType());
        $report->setEntityType($this->_getEntity());
        $report->setRequestId($request->getId());
        $report->setFailures(0);
        $report->setSuccesses(0);
        $report->setDuplicates(0);

        return $report;
    }

    /**
     * Creates a success report line.
     *
     * @param Varien_Object $report
     * @param string        $sequenceId
     * @param string        $reference
     * @param string        $message
     *
     * @return Varien_Object
     */
    protected function _createSuccessReportLine($report, $sequenceId, $reference, $message)
    {
        $report->setSuccesses($report->getSuccesses() + 1);

        return $this->_createReportLine(
            $reference,
            $sequenceId,
            $message,
            'Success',
            $report->additionalData
        );
    }

    /**
     * Creates a duplicate report line.
     *
     * @param Varien_Object $report
     * @param string        $sequenceId
     * @param string        $reference
     * @param string        $message
     *
     * @return Varien_Object
     */
    protected function _createDuplicateReportLine($report, $sequenceId, $reference, $message)
    {
        $report->setDuplicates($report->getDuplicates() + 1);

        return $this->_createReportLine($reference, $sequenceId, $message, 'Duplicate');
    }

    /**
     * Creates a failure report line.
     *
     * @param Varien_Object $report
     * @param string        $sequenceId
     * @param string        $reference
     * @param string        $message
     *
     * @return Varien_Object
     */
    protected function _createFailureReportLine($report, $sequenceId, $reference, $message)
    {
        $report->setFailures($report->getFailures() + 1);

        return $this->_createReportLine($reference, $sequenceId, $message, 'Failure');
    }

    /**
     * Creates a report line.
     *
     * @param string $reference
     * @param string $sequenceId
     * @param string $message
     * @param string $result
     *
     * @return Varien_Object
     */
    protected function _createReportLine($reference, $sequenceId, $message, $result, $additionalData = array())
    {
        $this->_processedIds[$sequenceId] = $sequenceId;

        return new Varien_Object(array(
            'reference'      => $reference,
            'sequenceId'     => $sequenceId,
            'message'        => $message,
            'detail'         => $message,
            'result'         => $result,
            'operation'      => 'Update',
            'entity'         => $this->_getEntity(),
            'timestamp'      => date('Y-m-d H:i:s'),
            'additionalData' => $additionalData
        ));
    }

    /**
     * Checks whether a request line has been previously processed.
     *
     * @param float $reference
     *
     * @return boolean
     */
    public function hasLineBeenPreviouslyProcessed($reference)
    {
        if (isset($this->_processedIds[$reference])) {
            return true;
        }

        return SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection::isDuplicate(
            $this->_getEntity(),
            $reference
        );
    }

    /**
     * Returns the type of the importer.
     *
     * @return string
     */
    protected function _getType()
    {
        return 'Import';
    }

    /**
     * Checks whether the export is enabled.
     *
     * @return boolean
     */
    protected abstract function _isEnabled();

    /**
     * Performs an import.
     *
     * @param mixed $requests
     *
     * @return SixBySix_RealTimeDespatch_Model_Import
     */
    protected abstract function _import($requests);

    /**
     * Returns the entity type of the importer.
     *
     * @return string
     */
    protected abstract function _getEntity();

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