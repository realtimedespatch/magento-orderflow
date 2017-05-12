<?php

/**
 * Return Export Type Model.
 */
class SixBySix_RealTimeDespatch_Model_Export_Type_Return extends SixBySix_RealTimeDespatch_Model_Export_Type
{
    /**
     * {@inheritdoc}
     */
    public function updateEntities($lines, \DateTime $exportedAt)
    {
        $failureIds = array();
        $tx         = Mage::getModel('core/resource_transaction');

        foreach ($lines as $line) {
            $return = $line->getEntityInstance();

           if ($line->isSuccess()) {
                $tx->addObject($return->export($exportedAt));
            } else {
                $failureIds[] = $return->getId();
                $tx->addObject($return->setExportFailures($return->getExportFailures() + 1));
            }
        }

        $tx->save();

        if (count($failureIds) > 0) {
            $this->_sendFailureEmails($this->_getFailedReturns($failureIds));
        }
    }

    /**
     * Returns a collection of returns failing to export.
     *
     * @param array $failureIds
     *
     * @return Mage_Sales_Model_Resource_Returns_Collection
     */
    protected function _getFailedReturns(array $failureIds)
    {
        return Mage::getResourceModel('enterprise_rma/rma_collection')
            ->addFieldToFilter('export_failures', array('gteq' => 4))
            ->addFieldToFilter('entity_id', array('in' => array($failureIds)))
            ->load();
    }
}