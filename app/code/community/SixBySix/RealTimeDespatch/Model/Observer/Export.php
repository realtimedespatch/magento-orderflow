<?php

/**
 * Export Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Export
{
    /**
     * List of processed IDs.
     *
     * @var array
     */
    protected $_processedIds = array();

    /**
     * Handles a successful export.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function handleExportSuccess($event)
    {
        $report      = $event->getReport();
        $reportLines = $report->getLines();
        $export      = $this->_createExport($report);

        $export->setRequestBody($event['requestBody']);
        $export->setResponseBody($event['responseBody']);
        $export->setIsManual($event['isManual']);

        $tx = Mage::getModel('core/resource_transaction');
        $tx->addObject($export);

        foreach ($reportLines as $reportLine) {
            if (isset($this->_processedIds[$reportLine->getExternalReference()])) {
                continue;
            }

            $exportLine = $this->_createExportLine($export, $reportLine);

            $tx->addObject($exportLine);

            $this->_processedIds[$exportLine->getReference()] = $exportLine->getReference();
        }

        $tx->save();

        $export->updateEntities();
    }

    /**
     * Creates a new export instance.
     *
     * @param \SixBySix\RealtimeDespatch\Report\ImportReport $report
     *
     * @return SixBySix_RealTimeDespatch_Model_Export
     */
    protected function _createExport($report)
    {
        $export = Mage::getModel('realtimedespatch/export');

        $export->setType('Export');
        $export->setEntity($report->getEntityType());
        $export->setSuccesses($report->getNumberOfUniqueSuccesses());
        $export->setDuplicates($report->getNumberOfDuplicates());
        $export->setFailures($report->getNumberOfFailures());

        return $export;
    }

    /**
     * Creates a new export line instance.
     *
     * @param \SixBySix_RealTimeDespatch_Model_Export            $export
     * @param \SixBySix\RealtimeDespatch\Report\ImportReportLine $reportLine
     *
     * @return SixBySix_RealTimeDespatch_Model_Export
     */
    protected function _createExportLine($export, $reportLine)
    {
        $exportLine = Mage::getModel('realtimedespatch/export_line');

        $exportLine->setExport($export);
        $exportLine->setType($reportLine->getResult());
        $exportLine->setReference($reportLine->getExternalReference());
        $exportLine->setOperation(uc_words($reportLine->getOperation()));
        $exportLine->setEntity($export->getEntity());
        $exportLine->setMessage($reportLine->getMessage());
        $exportLine->setDetail($reportLine->getDetail());
        $exportLine->setProcessed($reportLine->getTimestamp());

        return $exportLine;
    }

    /**
     * Creates an admin message for an erroneous export.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function handleExportSave($event)
    {
        $export = $event->getObject();

        if ($export->getIsManual()) {
            $this->_createProcessSchedule($export);
        } else {
            $this->_updateProcessSchedule($export);
        }

        if ( ! $export->isObjectNew() || $export->isSuccess()) {
            $this->_resetProcessFailures($export);
            return;
        }

        $this->_setAdminFailureNotification($export);
    }

    /**
     * Creates an associated process schedule.
     *
     * @param \SixBySix_RealTimeDespatch_Model_Export $export
     *
     * @return void
     */
    protected function _createProcessSchedule($export)
    {
        $executed = date('Y-m-d H:i:s');
        $schedule = Mage::getModel('realtimedespatch/process_schedule')
            ->setType(strtolower($export->getType()))
            ->setEntity(strtolower($export->getEntity()))
            ->setParentId($export->getId())
            ->setMessageId($export->getId())
            ->setScheduled($executed)
            ->setExecuted($executed)
            ->setExecutedWithItems($executed)
            ->setStatus('success')
            ->save();

        $rsc   = Mage::getSingleton("core/resource");
        $write = $rsc->getConnection('core_write');
        $rps   = $rsc->getTableName('realtimedespatch_process_schedules');

        $sql = "DELETE FROM ".$rps."
                WHERE scheduled < ? AND entity_id != ? AND entity = ?";

        $write->query(
            $sql,
            array(
                $schedule->getExecuted(),
                $schedule->getId(),
                $schedule->getEntity(),
            )
        );
    }

    /**
     * Updates the associated process schedule.
     *
     * @param \SixBySix_RealTimeDespatch_Model_Export $export
     *
     * @return void
     */
    protected function _updateProcessSchedule($export)
    {
        $executed = date('Y-m-d H:i:s');
        $schedule = Mage::getModel('realtimedespatch/process_schedule')
            ->getCollection()
            ->addFieldToFilter('status', array(array('eq' => 'pending'), array('eq' => 'processing')))
            ->addFieldToFilter('entity', strtolower($export->getEntity()))
            ->addFieldToFilter('scheduled', array('lteq' => $executed))
            ->setOrder('scheduled', 'DESC')
            ->setPageSize(1, 1)
            ->getFirstItem();

        if ( ! $schedule->getId()) {
            return;
        }

        $schedule
            ->setParentId($export->getId())
            ->setMessageId($export->getId())
            ->setStatus('Processing')
            ->setExecuted($executed)
            ->setExecutedWithItems($executed)
            ->save();
    }

    /**
     * Resets the process linked to the export.
     *
     * @return void
     */
    protected function _resetProcessFailures($export)
    {
        Mage::getModel('realtimedespatch/process')
           ->getCollection()
           ->addFieldToFilter('entity', $export->getEntity())
           ->addFieldToFilter('type', $export->getType())
           ->load()
           ->getFirstItem()
           ->resetFailures()
           ->save();
    }

    /**
     * Sets an admin failure message.
     *
     * @return void
     */
    protected function _setAdminFailureNotification($export)
    {
        if ( ! $export->getAdminUrl()) {
            return;
        }

        $inbox = Mage::getModel('adminnotification/inbox');
        $inbox->parse(
            array(
                array(
                    'severity'    => Mage_AdminNotification_Model_Inbox::SEVERITY_MAJOR,
                    'date_added'  => date('Y-m-d H:i:s'),
                    'title'       => 'A recent Realtime Despatch OrderFlow sync reported problems.',
                    'description' => 'Please check the corresponding export for details',
                    'url'         => $export->getAdminUrl(),
                    'internal'    => true
                )
            )
        );

        $inbox->save();
    }
}