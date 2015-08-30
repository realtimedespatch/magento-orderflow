<?php

/**
 * Requests Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_RequestsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('realtimedespatch/requests');
    }

    /**
     * Displays a list of all requests.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Requests'));
        $this->loadLayout();

        $this->_setActiveMenu('realtimedespatch/requests');
        $this->renderLayout();
    }

    /**
     * Displays a single request.
     *
     * @return void
     */
    public function viewAction()
    {
        $id      = $this->getRequest()->getParam('id');
        $request = Mage::getModel('realtimedespatch/request')->load($id);

        $this->loadLayout();

        $this->getLayout()
             ->getBlock('view')
             ->setOrderflowRequest($request);

        $this->_title($this->__('Request '.$id))->_title($this->__('OrderFlow'));
        $this->_setActiveMenu('realtimedespatch/requests');
        $this->renderLayout();
    }

    /**
     * Processes a single request.
     *
     * @return void
     */
    public function processAction()
    {
        $id      = $this->getRequest()->getParam('id');
        $request = Mage::getModel('realtimedespatch/request')->load($id);

        if ( ! $request->getId()) {
            $this->_redirect('*/*/');
            return;
        }

        if ( ! $request->canProcess()) {
            Mage::getSingleton('core/session')->addError(
                'This request has already been processed.'
            );

            $this->_redirect('*/*/view', array('id' => $id));
            return;
        }

        try {
            $factory = Mage::getModel('realtimedespatch/factory_service_importer');
            $service = $factory->retrieve($request->getType());
            $service->import($request);

            Mage::getSingleton('core/session')->addSuccess(
                'The request has been successfully processed.'
            );
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError(
                'Error processing request: '.$ex->getMessage()
            );
        }

        $this->_redirect('*/*/view', array('id' => $id));
    }

    /**
     * Processes all outstanding requests.
     *
     * @return void
     */
    public function processAllAction()
    {
        $factory  = Mage::getModel('realtimedespatch/factory_service_importer');
        $requests = Mage::getResourceModel('realtimedespatch/request_collection')->getAllProcessableRequests();

        if (count($requests) == 0) {
            Mage::getSingleton('core/session')->addError(
                'There are no requests available to be processed.'
            );

            $this->_redirect('*/*/');
            return;
        }

        try {
            foreach ($requests as $request) {
                $service = $factory->retrieve($request->getType());
                $service->import($request);
            }

            Mage::getSingleton('core/session')->addSuccess(
                'The request has been successfully processed.'
            );
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError(
                'Error processing requests: '.$ex->getMessage()
            );
        }

        $this->_redirect('*/*/');
    }
}