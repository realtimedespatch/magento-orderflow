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
}