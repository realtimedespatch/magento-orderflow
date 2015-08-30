<?php

/**
 * Product Gridg Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Product_Grid
{
    /**
     * Adds the export column to the product grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addExportColumn($event)
    {
        $collection = $event->getCollection();

        if ( ! $collection)
            return;

        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Collection) {
            if (($productListBlock = Mage::app()->getLayout()->getBlock('products_list')) != false &&($productListBlock instanceof Mage_Adminhtml_Block_Catalog_Product)){
                $this->_addExportColumn($productListBlock->getChild('grid'));
            } else if(($block = Mage::app()->getLayout()->getBlock('admin.product.grid')) != false){
                $this->_addExportColumn($block);
            }
        }
    }

    /**
     * Adds the export column to the product grid.
     *
     * @param mixed $block
     *
     * @return void
     */
    protected function _addExportColumn($block)
    {
        if ( ! $block->getCollection()) {
            return;
        }

        $block->getCollection()->addAttributeToSelect('is_exported');

        $block->addColumnAfter(
            'is_exported',
            array (
                'header'       => Mage::helper ('realtimedespatch')->__('Exported To OrderFlow'),
                'width'        => '80px',
                'index'        => 'is_exported',
                'type'         => 'options',
                'options'      => array(1 => 'True', 0 => 'False'),
                'renderer'     => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Exported(),
                'align'        => 'center',
                'filter_index' => 'main_table.is_exported'
            ),
            'status'
        );

        $block->sortColumnsByOrder();

        $filterString = $block->getParam($block->getVarNameFilter(), null);
        $filters      = $block->helper ('adminhtml')->prepareFilterString($filterString);

        if (isset($filters['is_exported'])) {
            $block->getCollection()->addAttributeToFilter('is_exported', array('eq' => $filters['is_exported']));
        }
    }

    /**
     * Adds the export action to the product grid.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function addExportAction($event)
    {
        if ( ! Mage::helper('realtimedespatch/export_product')->isExportEnabled()){
            return;
        }

        $block = $event->getBlock();

        $block->getMassactionBlock()->addItem(
            'export',
            array(
             'label'   => Mage::helper('catalog')->__('Export To OrderFlow'),
             'url'     => $block->getUrl('*/*/exportToOrderFlow'),
             'confirm' => Mage::helper('catalog')->__('Are you sure?')
       ));
    }
}