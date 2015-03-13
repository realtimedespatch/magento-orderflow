<?php

/**
 * Process Schedule Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Process_Schedule_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/process_schedule');
    }

    /**
     * Returns the last process schedule with items.
     *
     * @param string $entity
     *
     * @return SixBySix_RealTimeDespatch_Model_Process_Schedule
     */
    public static function getLastScheduleWithItems($entity)
    {
        return Mage::getResourceModel('realtimedespatch/process_schedule_collection')
                    ->addFieldToFilter('parent_id', array('neq' => 'null'))
                    ->addFieldToFilter('entity', $entity)
                    ->setPageSize(1)
                    ->setCurPage(1)
                    ->getFirstItem();
    }
}