<?php

/**
 * Product Attributes Tab Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Catalog_Product_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Attributes
{
    /**
     * {@inheritdoc}
     */
    public function _toHtml()
    {
        $prefix = '';

        if ($this->getGroup()->getAttributeGroupName() == 'OrderFlow') {
            $block = $this->getLayout()->createBlock(
                'Mage_Core_Block_Template',
                'orderflow_info',
                array('template' => 'realtimedespatch/catalog/product/tab/orderflow.phtml')
            );

            $prefix = $block->toHtml();
        }

        return $prefix .''. parent::_toHtml();
    }
}
