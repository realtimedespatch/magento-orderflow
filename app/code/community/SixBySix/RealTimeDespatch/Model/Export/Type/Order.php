<?php

/**
 * Order Export Type Model.
 */
class SixBySix_RealTimeDespatch_Model_Export_Type_Order extends SixBySix_RealTimeDespatch_Model_Export_Type
{
    /**
     * {@inheritdoc}
     */
    public function updateEntities($lines)
    {
        $failureIds = array();
        $tx         = Mage::getModel('core/resource_transaction');

        foreach ($lines as $line) {
            $order = $line->getEntityInstance();

            if ($line->isSuccess()) {
                $tx->addObject($order->export());
            } else {
                $failureIds[] = $order->getId();
                $tx->addObject($order->setExportFailures($order->getExportFailures() + 1));
            }
        }

        $tx->save();

        if (count($failureIds) > 0) {
            $this->_sendFailureEmails($this->_getFailedOrders($failureIds));
        }
    }

    /**
     * Returns a collection of orders failing to export.
     *
     * @param array $failureIds
     *
     * @return Mage_Sales_Model_Resource_Orders_Collection
     */
    protected function _getFailedOrders(array $failureIds)
    {
        return Mage::getResourceModel('sales/order_collection')
                ->addFieldToFilter('export_failures', array('gteq' => 4))
                ->addFieldToFilter('entity_id', array('in' => array($failureIds)))
                ->load();
    }
}