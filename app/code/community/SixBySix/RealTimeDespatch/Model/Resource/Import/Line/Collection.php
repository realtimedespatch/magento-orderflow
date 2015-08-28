<?php

/**
 * Import Line Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource_Import_Line_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/import_line');
    }

    public static function getLatestImportLineByReference($type, $reference)
    {
        return Mage::getResourceModel('realtimedespatch/import_line_collection')
            ->addFieldToFilter('reference', $reference)
            ->addFieldToFilter('processed', array('notnull' => true))
            ->addOrder('sequence_id', 'DESC')
            ->setPageSize(1)
            ->setCurPage(1)
            ->getFirstItem();
    }
}