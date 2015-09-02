<?php

/**
 * Import Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Import
{
    /**
     * Handles a successful import.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function handleImportSuccess($event)
    {
        $report       = $event->getReport();
        $reportLines  = $report->getLines();
        $import       = $this->_createImport($report);
        $processed    = date('Y-m-d H:i:s');

        $tx = Mage::getModel('core/resource_transaction');
        $tx->addObject($import);

        foreach ($reportLines as $reportLine) {
            $tx->addObject($this->_createImportLine($import, $reportLine));
        }

        foreach ($event->lines as $requestLine) {
            $tx->addObject($requestLine->setProcessed($processed)->setSequenceId(intval($requestLine->sequence_id)));
        }

        if (is_array($event->lines)) {
            $import->setRequestBody($event->lines[0]->getRequestBody());
        } else {
            $import->setRequestBody($event->lines->getFirstItem()->getRequestBody());
        }

        $tx->save();
    }

    /**
     * Creates an import instance.
     *
     * @param mixed $report
     *
     * @return SixBySix_RealTimeDespatch_Model_Import
     */
    protected function _createImport($report)
    {
        $import = Mage::getModel('realtimedespatch/import');

        $import->setType($report->getType());
        $import->setEntity($report->getEntityType());
        $import->setSuccesses($report->getSuccesses());
        $import->setDuplicates($report->getDuplicates());
        $import->setFailures($report->getFailures());
        $import->setRequestBody($report->getRequestBody());
        $import->setRequestId($report->getRequestId());

        return $import;
    }

    /**
     * Creates a new import instance.
     *
     * @param \SixBySix_RealTimeDespatch_Model_Import            $import
     * @param \SixBySix\RealtimeDespatch\Report\ImportReportLine $reportLine
     *
     * @return SixBySix_RealTimeDespatch_Model_Import
     */
    protected function _createImportLine($import, $reportLine)
    {
        $importLine = Mage::getModel('realtimedespatch/import_line');

        $importLine->setImport($import);
        $importLine->setType($reportLine->getResult());
        $importLine->setSequenceId($reportLine['sequenceId']);
        $importLine->setReference($reportLine->getReference());
        $importLine->setOperation(uc_words($reportLine->getOperation()));
        $importLine->setEntity($import->getEntity());
        $importLine->setMessage($reportLine->getMessage());
        $importLine->setDetail($reportLine->getDetail());
        $importLine->setProcessed($reportLine->getTimestamp());

        return $importLine;
    }

    /**
     * Creates an admin message for an erroneous import.
     *
     * @param mixed $event
     *
     * @return null
     */
    public function handleImportSave($event)
    {
        $import = $event->getObject();

        $this->_updateProcessSchedule($import);

        if ( ! $import->isObjectNew() || $import->isSuccess()) {
            $this->_resetProcessFailures($import);
            return;
        }

        $this->_setAdminFailureNotification($import);
    }

    /**
     * Updates the associated process schedule.
     *
     * @param \SixBySix_RealTimeDespatch_Model_Import $import
     *
     * @return void
     */
    protected function _updateProcessSchedule($import)
    {
        $executed = date('Y-m-d H:i:s');
        $schedule = Mage::getModel('realtimedespatch/process_schedule')
            ->getCollection()
            ->addFieldToFilter('status', array(array('eq' => 'pending'), array('eq' => 'processing')))
            ->addFieldToFilter('entity', strtolower($import->getEntity()))
            ->addFieldToFilter('scheduled', array('lteq' => $executed))
            ->setOrder('scheduled', 'DESC')
            ->setPageSize(1, 1)
            ->getFirstItem();

        if ( ! $schedule->getId()) {
            return;
        }

        $schedule
            ->setParentId($import->getId())
            ->setMessageId($import->message_id)
            ->setStatus('Processing')
            ->setExecuted($executed)
            ->setExecutedWithItems($executed)
            ->save();
    }

    /**
     * Updates the process schedule linked to the import.
     *
     * @return void
     */
    protected function _resetProcessFailures($import)
    {
        Mage::getModel('realtimedespatch/process')
           ->getCollection()
           ->addFieldToFilter('entity', $import->getEntity())
           ->addFieldToFilter('type', $import->getType())
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
    protected function _setAdminFailureNotification($import)
    {
        if ( ! $import->getAdminUrl()) {
            return;
        }

        $inbox = Mage::getModel('adminnotification/inbox');
        $inbox->parse(
            array(
                array(
                    'severity'    => Mage_AdminNotification_Model_Inbox::SEVERITY_MAJOR,
                    'date_added'  => date('Y-m-d H:i:s'),
                    'title'       => 'A recent Realtime Despatch OrderFlow sync reported problems.',
                    'description' => 'Please check the corresponding import for details',
                    'url'         => $import->getAdminUrl(),
                    'internal'    => true
                )
            )
        );

        $inbox->save();
    }
}