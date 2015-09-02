<?php

/**
 * Bulk Export Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_BulkExportController extends Mage_Adminhtml_Controller_Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('realtimedespatch/catalogue/bulkexport');
    }

    /**
     * Index Action.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Bulk Catalogue Export'));
        $this->loadLayout();

        $this->getLayout()
             ->getBlock('messages')
             ->addNotice('Use the OrderFlow -> Exports -> Products menu to view progress. Alternatively, view the product import history in the <a target="_blank" href="'.$this->_getOrderFlowImportUrl().'">OrderFlow import menu.</a>');

        $this->renderLayout();
    }

    /**
     * Export Action.
     *
     * @return void
     */
    public function exportAction()
    {
        try {
            Mage::getSingleton('catalog/product_action')->updateAttributes(
                Mage::getResourceModel('catalog/product_collection')->getAllIds(),
                array(
                    'export_failures' => null,
                    'is_exported' => null,
                    'exported_at' => null
                ),
                Mage_Core_Model_App::ADMIN_STORE_ID
            );

            $export = Mage::getModel('realtimedespatch/bulk_export')->load(
                SixBySix_RealTimeDespatch_Model_Bulk_Export::TYPE_CATALOG,
                'type'
            );

            $export->setExecuted(date('Y-m-d h:i:s'));
            $export->save();

            Mage::getSingleton('core/session')->addSuccess(
                'Bulk catalogue export initiated.'
            );
        } catch (Exception $ex) {
            Mage::getSingleton('core/session')->addError(
                'Error processing export: '.$ex->getMessage()
            );
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Returns the url to order flow import menu.
     *
     * @return string
     */
    protected function _getOrderFlowImportUrl()
    {
        $helper = Mage::helper('realtimedespatch');

        return $helper->getApiEndpoint().'import/view/dashboard.htm?menu=import';
    }
}