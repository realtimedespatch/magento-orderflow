<?php

/**
 * Inventory Imports Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_InventoryImportsController extends Mage_Adminhtml_Controller_Action
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
        $this->_title($this->__('OrderFlow'))->_title($this->__('Inventory Imports'));
        $this->loadLayout();

        $this->getLayout()
             ->getBlock('imports')
             ->setOperationType('Import')
             ->setEntityType('Inventory')
             ->setReferenceLabel('Unique SKUs');

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
             ->setEntityType('Inventory')
             ->setReferenceLabel('SKU');

        $this->_title($this->__('Inventory Import '.$id))->_title($this->__('OrderFlow'));
        $this->_setActiveMenu('realtimedespatch/imports');
        $this->renderLayout();
    }
}