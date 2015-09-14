<?php

/**
 * Order Exports Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_OrderExportsController extends Mage_Adminhtml_Controller_Action
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
        return Mage::getSingleton('admin/session')->isAllowed('realtimedespatch/exports/orderexports');
    }

    /**
     * Displays a list of all exports.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Order Exports'));
        $this->loadLayout();

        $this->getLayout()
             ->getBlock('exports')
             ->setOperationType('Export')
             ->setEntityType('Order')
             ->setReferenceLabel('Unique Order IDs');

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
             ->setEntityType('Order')
             ->setReferenceLabel('Order ID');

        $this->_title($this->__('Order Export '.$id))->_title($this->__('OrderFlow'));
        $this->_setActiveMenu('realtimedespatch/exports');
        $this->renderLayout();
    }
}