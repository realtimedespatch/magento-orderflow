<?php

/**
 * Shipment Imports Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_ShipmentImportsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * {@inheritdoc}
     */
    protected $_publicActions = array('view');

    /**
     * Displays a list of all imports.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Shipment Imports'));
        $this->loadLayout();

        $this->getLayout()
             ->getBlock('imports')
             ->setOperationType('Import')
             ->setEntityType('Shipment')
             ->setReferenceLabel('Unique Shipment IDs');

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
             ->setEntityType('Shipment')
             ->setReferenceLabel('Shipment ID');

        $this->_title($this->__('Shipment Import '.$id))->_title($this->__('OrderFlow'));
        $this->_setActiveMenu('realtimedespatch/imports');
        $this->renderLayout();
    }
}