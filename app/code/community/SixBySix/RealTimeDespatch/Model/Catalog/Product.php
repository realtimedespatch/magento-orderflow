<?php

/**
 * Product Model.
 */
class SixBySix_RealTimeDespatch_Model_Catalog_Product extends Mage_Catalog_Model_Product implements SixBySix_RealTimeDespatch_Model_Interface_Exportable
{
    /**
     * Returns the product's current currency code.
     *
     * @return string
     */
    public function getCurrency()
    {
        return Mage::app()->getStore()->getCurrentCurrencyCode();
    }

    /**
     * Returns the product's current category name.
     *
     * @return string
     */
    public function getCategoryName()
    {
        if ( ! $this->getCategory()) {
            return null;
        }

        return $this->getCategory()->getName();
    }

    /**
     * Returns the current tax price.
     *
     * Gross Price - Net Price.
     *
     * @return float
     */
    public function getTaxPrice()
    {
        return $this->getGrossPrice() - $this->getNetPrice();
    }

    /**
     * Returns the current tax price.
     *
     * Final Price.
     *
     * @return float
     */
    public function getNetPrice()
    {
        return (float) Mage::helper('tax')->getPrice($this, $this->getFinalPrice());
    }

    /**
     * Returns the current gross price.
     *
     * Final Price Including Tax.
     *
     * @return float
     */
    public function getGrossPrice()
    {
        return (float) Mage::helper('tax')->getPrice($this, $this->getFinalPrice(), true);
    }

    /**
     * Returns the product's current tax class.
     *
     * @return string
     */
    public function getTaxClass()
    {
        return Mage::getModel('tax/class')
            ->load($this->getTaxClassId(), 'class_id')
            ->getClassName();
    }

    /**
     * {@inheritdoc]
     */
    public function getExportReference()
    {
        return $this->getSku();
    }

    /**
     * {@inheritdoc]
     */
    public function getExportType()
    {
        return 'Product';
    }

    /**
     * {@inheritdoc]
     */
    public function export()
    {
       $this->setIsExported(true)
            ->setExportedTimestamp(true);

        return $this;
    }
}