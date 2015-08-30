<?php

/**
 * Request Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Request_Collection extends SixBySix_RealTimeDespatch_Model_Resource
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
     * Returns the next requests to process.
     *
     * @param string  $type
     * @param integer $batchLimit
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection
     */
    public function getNextRequests($type, $batchLimit)
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
        $requests->setPageSize($batchLimit);
        $requests->setCurPage(1);

        return $requests;
    }
}