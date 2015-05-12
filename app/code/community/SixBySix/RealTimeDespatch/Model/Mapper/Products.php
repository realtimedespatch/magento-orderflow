<?php

use \SixBySix\RealtimeDespatch\Entity\ProductCollection as RTDProductCollection;
use \SixBySix\RealtimeDespatch\Entity\Product as RTDProduct;

/**
 * Products Mapper.
 *
 * Maps between Magento and Real Time Product Models.
 */
class SixBySix_RealTimeDespatch_Model_Mapper_Products extends Mage_Core_Helper_Abstract
{
    /**
     * Encodes a Magento Product Collection.
     *
     * @param Mage_Catalog_Model_Resource_Product_Collection $products
     *
     * @return RTDProductCollection
     */
    public function encode(Mage_Catalog_Model_Resource_Product_Collection $products)
    {
        $encodedProducts = new RTDProductCollection;

        foreach ($products as $product) {
            $encodedProducts[] = $this->_encodeProduct($product);
        }

        return $encodedProducts;
    }

    /**
     * Encodes a single Magento Product.
     *
     * @param Mage_Catalog_Model_Product $product
     *
     * @return RTDProduct
     */
    protected function _encodeProduct(Mage_Catalog_Model_Product $product)
    {
        $encodedProduct = new RTDProduct;
        $customMapping  = Mage::getConfig()->getNode('rtd_mappings/product/export');
	    $attributeTypes = array('multiselect', 'dropdown');

        foreach ($customMapping->asArray() as $magentoKey => $rtdKey) {
            try {
	            $attribute = $product->getResource()->getAttribute($magentoKey);
	            if($attribute != false && in_array($attribute->getFrontendInput(), $attributeTypes)) {
			$encodedProduct->setParam($rtdKey, $product->getAttributeText($magentoKey));
	            }
	            else {
			$encodedProduct->setParam($rtdKey, $product->{'get'.$magentoKey}());
	            }
            }
            catch (Exception $ex) {
                Mage::log($ex->getMessage(), null, 'realtimedespatch.log');
            }
        }

        $this->_swapPlaceholderWithParentImage($product, $encodedProduct);
        return $encodedProduct;
    }

    /**
     * If product doesn't have an image of it's own, attempt to
     * replace the placeholder with parent's image (if available)
     *
     * @param Mage_Catalog_Model_Product $product
     * @param RTDProduct $rtdProduct
     */
    protected function _swapPlaceholderWithParentImage(Mage_Catalog_Model_Product $product, RTDProduct $rtdProduct)
    {
        if ($product->getSmallImage() == 'no_selection' || strlen($product->getSmallImage()) < 1) {

            $productTypes = array(
                'catalog/product_type_configurable',
                'catalog/product_type_grouped',
                'bundle/product_type'
            );

            $parentId = null;
            foreach ($productTypes as $type) {
                $parentIds = Mage::getModel($type)->getParentIdsByChild($product->getId());

                if (count($parentIds) > 0) {
                    $parentId = $parentIds[0];
                    break;
                }
            }

            if ($parentId != null) {
                $parentProduct = Mage::getModel('catalog/product')->load($parentId);
                $rtdProduct->setParam('imageReference', $parentProduct->getSmallImageUrl());
            }
        }
    }
}
