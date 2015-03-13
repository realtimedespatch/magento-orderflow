<?php

/**
 * Request Line Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    const REQUEST_LINE_TYPE_INVENTORY = 'Inventory';
    const REQUEST_LINE_TYPE_SHIPMENT  = 'Shipment';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/request_line');
    }

    /**
     * Returns the Sequence ID of the next request to process.
     *
     * @param string $type
     *
     * @return integer
     */
    public static function getNextSequencesId($type)
    {
        return Mage::getResourceModel('realtimedespatch/request_line_collection')
                    ->addFieldToFilter('type', array('eq' => $type))
                    ->addFieldToFilter('processed', array('null' => true))
                    ->setOrder('sequence_id', 'ASC')
                    ->setPageSize(1)
                    ->setCurPage(1)
                    ->getFirstItem()
                    ->getSequenceId();
    }

    /**
     * Returns the ID of the next request to process.
     *
     * @param string $type
     *
     * @return integer
     */
    public function getNextRequestId($type)
    {
        return Mage::getResourceModel('realtimedespatch/request_line_collection')
                    ->addFieldToFilter('type', array('eq' => $type))
                    ->addFieldToFilter('processed', array('null' => true))
                    ->setOrder('sequence_id', 'ASC')
                    ->setPageSize(1)
                    ->setCurPage(1)
                    ->getFirstItem()
                    ->getRequestId();
    }

    /**
     * Returns the next request lines to process.
     *
     * @param string  $type
     * @param integer $batchLimit
     *
     * @return SixBySix_RealTimeDespatch_Model_Resource_Request_Line_Collection
     */
    public function getNextRequestLines($type, $batchLimit)
    {
        return Mage::getResourceModel('realtimedespatch/request_line_collection')
                    ->addFieldToFilter('type', array('eq' => $type))
                    ->addFieldToFilter('processed', array('null' => true))
                    ->addFieldToFilter('request_id', $this->getNextRequestId($type))
                    ->setOrder('sequence_id', 'ASC')
                    ->setPageSize($batchLimit)
                    ->setCurPage(1);
    }

    /**
     * Checks whether a duplicate request line exists.
     *
     * @param string $type
     * @param float  $sequenceId
     *
     * @return boolean
     */
    public static function isDuplicate($type, $sequenceId)
    {
        $numLines = Mage::getResourceModel('realtimedespatch/request_line_collection')
                         ->addFieldToFilter('type', $type)
                         ->addFieldToFilter('sequence_id', $sequenceId)
                         ->addFieldToFilter('processed', array('notnull' => true))
                         ->load();

        return count($numLines) >= 1;
    }
}