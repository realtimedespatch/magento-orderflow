<?php

/**
 * Product Exports Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_ProductExportsController extends Mage_Adminhtml_Controller_Action
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
        return Mage::getSingleton('admin/session')->isAllowed('realtimedespatch/productexports');
    }

    /**
     * Displays a list of all exports.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Product Exports'));
        $this->loadLayout();

        $this->getLayout()
             ->getBlock('exports')
             ->setOperationType('Export')
             ->setEntityType('Product')
             ->setReferenceLabel('Unique SKUs');

        $this->_setActiveMenu('realtimedespatch/exports');
        $this->renderLayout();
    }

    /**
     * Displays a single export.
     *
     * @return void
     */
    public function viewAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $export = Mage::getModel('realtimedespatch/export')->load($id);

        $this->loadLayout();

        $this->getLayout()
             ->getBlock('view')
             ->setExport($export)
             ->setOperationType('Export')
             ->setEntityType('Product')
             ->setReferenceLabel('SKU');

        $this->_title($this->__('Product Export '.$id))->_title($this->__('OrderFlow'));
        $this->_setActiveMenu('realtimedespatch/exports');
        $this->renderLayout();
    }
}