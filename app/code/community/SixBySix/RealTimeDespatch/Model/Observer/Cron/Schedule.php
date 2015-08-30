<?php

/**
 * Cron Schedule Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Cron_Schedule
{
    /**
     * Process White List.
     *
     * @var array
     */
    protected $_processWhitelist = array(
        'orderflow_shipment_import',
        'orderflow_inventory_import',
        'orderflow_order_export',
        'orderflow_product_export',
    );

    /**
     * Handles the update / creation of a cron schedule.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function onCronScheduleSave($event)
    {
        $schedule = $event->getObject();

        if ( ! $this->_canProcess($schedule)) {
            return;
        }

        $this->_updateProcessSchedule($schedule);
    }

    /**
     * Updates a new process schedule.
     *
     * @param Mage_Cron_Model_Schedule $schedule
     */
    protected function _updateProcessSchedule($schedule)
    {
        $this->_cleanPreviousEntries(
            $this->_createProcessSchedule($schedule)
        );
    }

    /**
     * Create process schedules.
     *
     * @param Mage_Cron_Model_Schedule $schedule
     *
     * @return void
     */
    protected function _createProcessSchedule($schedule)
    {
        $isNew             = $schedule->isObjectNew();
        $parts             = explode('_', $schedule->getJobCode());
        $scheduleWithItems = SixBySix_RealTimeDespatch_Model_Resource_Process_Schedule_Collection::getLastScheduleWithItems($parts[0]);

        $schedule = Mage::getModel('realtimedespatch/process_schedule')
            ->load($schedule->getId(), 'cron_id')
            ->setCronId($schedule->getId())
            ->setStatus($schedule->getStatus())
            ->setScheduled($schedule->getScheduledAt())
            ->setExecuted($schedule->getExecutedAt())
            ->setType($parts[1])
            ->setEntity($parts[0]);

        if ($schedule->getStatus() == Mage_Cron_Model_Schedule::STATUS_MISSED) {
            $schedule->setExecuted(date('Y-m-d H:i:s'));
        }

        if (( ! $schedule->getParentId() && ! $isNew) && $scheduleWithItems->getId()) {
            $schedule->setParentId($scheduleWithItems->getParentId());
            $schedule->setMessageId($scheduleWithItems->getMessageId());
            $schedule->setExecutedWithItems($scheduleWithItems->getExecutedWithItems());
        }

        $schedule->save();

        return $schedule;
    }

    /**
     * Cleans the previous process schedules.
     *
     * @param Mage_Cron_Model_Schedule $cronSchedule
     *
     * @return void
     */
    protected function _cleanPreviousEntries($schedule)
    {
        $rsc   = Mage::getSingleton("core/resource");
        $write = $rsc->getConnection('core_write');
        $rps   = $rsc->getTableName('realtimedespatch_process_schedules');

        $sql = "DELETE FROM ".$rps."
                WHERE status != 'pending' AND status != 'processing' AND scheduled < ? AND entity_id != ? AND entity = ?";

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
     * Handles the deletion of a cron schedule.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function onCronScheduleDelete($event)
    {
        $schedule = $event->getObject();

        if ( ! $this->_canProcess($schedule)) {
            return;
        }

        $process = Mage::getModel('realtimedespatch/process_schedule')->load($schedule->getId(), 'cron_id');

        if ($process->getStatus() === 'pending') {
            $process->delete();
        }
    }

    /**
     * Checks whether we can process the cron schedule.
     *
     * @param mixed $schedule
     *
     * @return void
     */
    protected function _canProcess($schedule)
    {
        if ( ! ($schedule instanceof Mage_Cron_Model_Schedule)) {
            return false;
        }

        if ( ! in_array($schedule->getJobCode(), $this->_processWhitelist)) {
            return false;
        }

        return true;
    }
}