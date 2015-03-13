<?php

/**
 * Collection Resource Model.
 */
class SixBySix_RealTimeDespatch_Model_Resource extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Fixes grid group by pagination issue.
     *
     * See: http://raisereview.com/wrong-grid-count-and-pagination-issue-in-magento-admin-grid/
     *
     * @return string
     */
    public function getSelectCountSql()
    {
        $this->_renderFilters();
            $countSelect = clone $this->getSelect();
            $countSelect->reset(Zend_Db_Select::ORDER);
            $countSelect->reset(Zend_Db_Select::LIMIT_COUNT);
            $countSelect->reset(Zend_Db_Select::LIMIT_OFFSET);
            $countSelect->reset(Zend_Db_Select::COLUMNS);
            if(count($this->getSelect()->getPart(Zend_Db_Select::GROUP)) > 0) {
            $countSelect->reset(Zend_Db_Select::GROUP);
            $countSelect->distinct(true);
            $group = $this->getSelect()->getPart(Zend_Db_Select::GROUP);
            $countSelect->columns("COUNT(DISTINCT ".implode(", ", $group).")");
        } else {
            $countSelect->columns('COUNT(*)');
        }

        return $countSelect;
    }
}