<?php

/**
 * Request Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Request_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
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
     * Returns the next processable request of a certain type.
     *
     * @param string $type
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection
     */
    public function getNextProcessableRequestWithType($type)
    {
        return $this->getProcessableRequestsWithType($type)
                    ->setPageSize(1)
                    ->setCurPage(1)
                    ->getFirstItem();
    }

    /**
     * Returns all processable requests.
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection
     */
    public function getAllProcessableRequests()
    {
        $requests = Mage::getResourceModel('realtimedespatch/request_collection');

        $requests->getSelect()->join(
                array('request_line'=> Mage::getSingleton('core/resource')->getTableName('realtimedespatch_request_lines')),
                'request_line.request_id = main_table.entity_id',
                array('request_line.processed')
            )
            ->group('main_table.entity_id');
        $requests->addFieldToFilter('request_line.processed', array('null' => true));
        $requests->setOrder('message_id', 'ASC');

        return $requests;
    }

    /**
     * Returns all processable requests of a specific type.
     *
     * @param string  $type
     * @param integer $batchLimit
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection
     */
    public function getProcessableRequestsWithType($type, $batchLimit = null)
    {
        $requests = Mage::getResourceModel('realtimedespatch/request_collection');
        $requests->addFieldToFilter('main_table.type', array('eq' => $type));

        $requests->getSelect()->join(
                array('request_line'=> Mage::getSingleton('core/resource')->getTableName('realtimedespatch_request_lines')),
                'request_line.request_id = main_table.entity_id',
                array('request_line.processed')
            )
            ->group('main_table.entity_id');
        $requests->addFieldToFilter('request_line.processed', array('null' => true));

        if ($batchLimit) {
            $requests->setPageSize($batchLimit);
            $requests->setCurPage(1);
        }

        return $requests;
    }
}