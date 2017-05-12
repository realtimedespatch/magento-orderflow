<?php

/**
 * Log Cleaning Service.
 */
class SixBySix_RealTimeDespatch_Model_Service_Return_Closure
{
    /**
     * Closes all open returns that were created before the configured cutoff date.
     *
     * @return SixBySix_RealTimeDespatch_Model_Service_Return_Closure
     */
    public function closeReturns()
    {
        $helper = Mage::helper('realtimedespatch/log_cleaning');

        if ( ! $helper->isReturnClosureEnabled()) {
            return $this;
        }

        $this->_closeReturns($helper->getReturnCutoffDate());

        return $this;
    }

    /**
     * Closes all open returns that were created before the configured cutoff date.
     *
     * @param \DateTime $cutoffDate
     *
     * @return void
     */
    protected function _closeReturns($cutoffDate)
    {
        $expiredReturns = $this->_getExpiredReturns($cutoffDate);

        foreach ($expiredReturns as $expiredReturn) {
            $expiredReturn->close()->save();
        }
    }

    /**
     * Returns the set of expired returns.
     *
     * @param $cutoffDate
     *
     * @return mixed
     */
    protected function _getExpiredReturns($cutoffDate)
    {
        return Mage::getResourceModel('enterprise_rma/rma_collection')
            ->addFieldToFilter(
                'date_requested',
                array('lt' => $cutoffDate->format('Y-m-d'))
            )
            ->addFieldToFilter(
                'status',
                array('nin' => array('closed', 'processed_closed')
                )
            );
    }
}