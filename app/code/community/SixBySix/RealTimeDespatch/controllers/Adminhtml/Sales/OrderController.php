<?php

require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';

/**
 * Adminhtml Sales Order Controller
 */
class SixBySix_RealTimeDespatch_Adminhtml_Sales_OrderController extends Mage_Adminhtml_Sales_OrderController
{
    /**
     * Exports a set of orders to Orderflow.
     *
     * @return void
     */
    public function exportToOrderFlowAction()
    {
        $orders = Mage::getResourceModel('sales/order_collection');
        $orders->addFieldToFilter('entity_id', array('in' => (array) $this->getRequest()->getParam('order_ids')))
               ->addFieldToFilter('is_virtual', array('eq' => 0))
               ->addFieldToFilter('state', array('eq' => Mage_Sales_Model_Order::STATE_PROCESSING))
               ->load();

        if ( ! count($orders) > 0) {
            $this->_getSession()->addError($this->__('No exportable (invoiced) orders selected.'));
            $this->_redirect('*/*/index');
            return;
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_exporter');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Exporter::EXPORTER_ORDER_EXPORT);

        try
        {
            $report = $service->export($orders, true);

            $this->_getSession()->addSuccess(
                $this->__('Exported '.count($orders).' orders(s) to OrderFlow with '.$report->getNumberOfFailures().' failures.')
            );
        }
        catch (Exception $ex)
        {
            $this->_getSession()->addError($this->__('Error Exporting Order(s) to OrderFlow: '.$ex->getMessage()));
        }

        $this->_redirect('*/*/index');
    }
}