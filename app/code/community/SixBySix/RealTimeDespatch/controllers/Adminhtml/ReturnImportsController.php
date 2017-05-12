<?php

/**
 * Return Imports Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_ReturnImportsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * {@inheritdoc}
     */
    protected $_publicActions = array('view');

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('realtimedespatch/imports/returnimports');
    }

    /**
     * Displays a list of all imports.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Return Imports'));
        $this->loadLayout();

        $this->getLayout()
             ->getBlock('imports')
             ->setOperationType('Import')
             ->setEntityType('Return')
             ->setReferenceLabel('Total Return Lines');

        $this->_setActiveMenu('realtimedespatch/imports');
        $this->renderLayout();
    }

    /**
     * Displays a single import.
     *
     * @return void
     */
    public function viewAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $import = Mage::getModel('realtimedespatch/import')->load($id);

        $this->loadLayout();

        $this->getLayout()
             ->getBlock('view')
             ->setImport($import)
             ->setOperationType('Import')
             ->setEntityType('Return')
             ->setReferenceLabel('Return ID');

        $this->_title($this->__('Return Import '.$id))->_title($this->__('OrderFlow'));
        $this->_setActiveMenu('realtimedespatch/imports');
        $this->renderLayout();
    }
}