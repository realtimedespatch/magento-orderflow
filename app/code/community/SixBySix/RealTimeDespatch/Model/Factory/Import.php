<?php

/**
 * Entity Factory.
 */
class SixBySix_RealTimeDespatch_Model_Factory_Entity
{
    const ENTITY_PRODUCT   = 'Product';
    const ENTITY_ORDER     = 'Order';
    const ENTITY_INVENTORY = 'Inventory';
    const ENTITY_SHIPMENT  = 'Shipment';

    /**
     * Retrieves an entity.
     *
     * @param string $type
     * @param mixed  $reference
     */
    public function retrieve($type, $reference)
    {
        switch ($type) {
            case self::ENTITY_PRODUCT:
            case self::ENTITY_INVENTORY:
                return Mage::getModel('catalog/product')->loadByAttribute('sku', $reference);
            break;
            case self::ENTITY_ORDER:
                return Mage::getModel('sales/order')->loadByAttribute('increment_id', $reference);
            break;
            case self::ENTITY_SHIPMENT:
                return Mage::getModel('sales/order_shipment')->load($reference, 'increment_id');
            break;
            default:
                throw new Exception('Invalid Entity Type');
            break;
        }
    }

    /**
     * Retrieves an import url.
     *
     * @param string $type
     * @param mixed  $reference
     */
    public function retrieveAdminUrl($type, $reference)
    {
        switch ($type) {
            case self::ENTITY_PRODUCT:
            case self::ENTITY_INVENTORY:
                return $this->_retrieveAdminUrl(
                    Mage::helper('adminhtml')->getUrl('adminhtml/catalog_product/edit/') .'id/',
                    $this->retrieve($type, $reference)
                );
            break;
            default:
                throw new Exception('Invalid Import Type');
            break;
        }
    }

    /**
     * Returns the admin url.
     *
     * @param string $url
     * @param mixed  $entity
     *
     * @return string
     */
    protected function _retrieveAdminUrl($url, $entity = null)
    {
        if ( ! $entity) {
            return '';
        }

        return $url.$entity->getId();
    }
}