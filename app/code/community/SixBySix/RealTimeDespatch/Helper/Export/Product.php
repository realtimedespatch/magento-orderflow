<?php

/**
 * Product Export Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Export_Product extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the product export is enabled.
     *
     * @return boolean
     */
    public function isExportEnabled()
    {
        if ( ! Mage::helper('realtimedespatch')->isEnabled()) {
            return false;
        }

        return (boolean) Mage::getStoreConfig('sixbysix_realtimedespatch/product_export/is_enabled');
    }

    /**
     * Returns the current source store identifier.
     *
     * @return integer
     */
    public function getStoreId()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/product_export/store_id');
    }

    /**
     * Returns the current product export batch limit.
     *
     * @return integer
     */
    public function getBatchLimit()
    {
        return (integer) Mage::getStoreConfig('sixbysix_realtimedespatch/product_export/batch_size');
    }

    /**
     * Returns a list of products that can be exported.
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getExportableProducts()
    {
        return Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('type_id', 'simple')
                ->addAttributeToFilter(
                    array(
                        array('attribute' => 'export_failures', 'null' => true),
                        array('attribute'=> 'export_failures','lt' => 4)
                    ),
                '',
                'left'
                )
                ->addAttributeToFilter(
                    array(
                        array('attribute' => 'is_exported', 'null' => true),
                        array('attribute'=> 'is_exported','eq' => 0)
                    ),
                '',
                'left'
                )
                ->addAttributeToFilter(
                    array(
                        array('attribute' => 'exported_at', 'null' => true),
                        array('attribute'=> 'exported_at','lt' => new Zend_Db_Expr('updated_at'))
                    ),
                '',
                'left'
                )
                ->addStoreFilter($this->getStoreId())
                ->setPageSize($this->getBatchLimit())
                ->setCurPage(1);
    }

    /**
     * Disables the product export cron process.
     *
     * @return void
     */
    public function disable()
    {
        Mage::getConfig()->saveConfig('sixbysix_realtimedespatch/product_export/is_enabled', false);
        Mage::getConfig()->reinit();
        Mage::app()->reinitStores();
    }

    /**
     * Staggers the product export cron process.
     *
     * @param integer $minutes
     *
     * @return void
     */
    public function stagger($minutes)
    {
        Mage::getSingleton('core/resource')
            ->getConnection('core_write')
            ->query("UPDATE ".Mage::getSingleton('core/resource')->getTableName('cron_schedule')." SET scheduled_at = DATE_ADD(scheduled_at, INTERVAL ".$minutes." MINUTE) WHERE job_code = 'product_export' AND status = 'pending'");
    }
}