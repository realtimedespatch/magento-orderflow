<?php

require_once 'Enterprise/Rma/controllers/Adminhtml/RmaController.php';

/**
 * Adminhtml Sales Order Controller
 */
class SixBySix_RealTimeDespatch_Adminhtml_RmaController extends Enterprise_Rma_Adminhtml_RmaController
{
    /**
     * Exports an RMA to Orderflow.
     *
     * @return void
     */
    public function exportToOrderFlowAction()
    {
        $returnId = $this->getRequest()->getParam('rma_id');
        $return = Mage::getModel('enterprise_rma/rma')->load($returnId);

        if ( ! $return->getId()) {
            $this->_getSession()->addError($this->__('RMA Not Found.'));
            return $this->_redirect('*/*/index');
        }

        if ($return->hasPendingItems()) {
            $this->_getSession()->addError($this->__('All pending items must be addressed before this return can be exported.'));
            return $this->_redirect('*/*/index');
        }

        $factory = Mage::getModel('realtimedespatch/factory_service_exporter');
        $service = $factory->retrieve(SixBySix_RealTimeDespatch_Model_Factory_Service_Exporter::EXPORTER_RETURN_EXPORT);
        $returns = array($return);

        try {
            $report = $service->export($returns, true);

            $this->_getSession()->addSuccess(
                $this->__('RMA exported to OrderFlow with '.$report->getNumberOfFailures().' failures.')
            );
        }
        catch (Exception $ex) {
            $this->_getSession()->addError($this->__('Error Exporting Return to OrderFlow: '.$ex->getMessage()));
        }

        $this->_redirect('*/*/index');
    }
}