<?php

/**
 * Product Export Type Model.
 */
class SixBySix_RealTimeDespatch_Model_Export_Type_Product extends SixBySix_RealTimeDespatch_Model_Export_Type
{
    /**
     * {@inheritdoc}
     */
    public function updateEntities($lines)
    {
        $successIds  = array();
        $failureIds  = array();

        foreach ($lines as $line) {
            $entity = $line->getEntityInstance();

            if ( ! $entity) {
                continue;
            }

            if ($line->isSuccess()) {
                $successIds[] = $entity->getId();
            } else {
                $failureIds[] = $entity->getId();
            }
        }

        $this->_updateSuccessfulImports($successIds);

        if (count($failureIds) > 0) {
            $this->_updateFailedImports($failureIds);
            $this->_sendFailureEmails($this->_getFailedProducts($failureIds));
        }
    }

    /**
     * Updates the orders that have successfully exported.
     *
     * @param array $successIds
     *
     * @return void
     */
    protected function _updateSuccessfulImports($successIds)
    {
        $successData = array('is_exported' => 1, 'exported_at' => date('Y-m-d H:i:s'), 'export_failures' => 0);

        Mage::getSingleton('catalog/product_action')->updateAttributes(
            $successIds,
            $successData,
            Mage_Core_Model_App::ADMIN_STORE_ID
        );
    }

    /**
     * Updates the products that have failed to export.
     *
     * @param array $successIds
     *
     * @return void
     */
    protected function _updateFailedImports($failureIds)
    {
        $eavAttribute = new Mage_Eav_Model_Mysql4_Entity_Attribute();
        $attrId       = $eavAttribute->getIdByCode('catalog_product', 'export_failures');
        $resource     = Mage::getSingleton('core/resource');
        $write        = $resource->getConnection('core_write');
        $cpei         = $resource->getTableName('catalog_product_entity_int');

        $sql = "UPDATE ".$cpei." cpei
                SET
                cpei.value = cpei.value + 1
                WHERE
                cpei.entity_id = ? AND cpei.attribute_id = ?;";

        foreach ($failureIds as $failureId) {
            $write->query($sql, array($failureId, $attrId));
        }
    }

    /**
     * Returns a collection of products failing to export.
     *
     * @param array $failureIds
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getFailedProducts(array $failureIds)
    {
        return Mage::getResourceModel('catalog/product_collection')
                ->addAttributeToFilter('export_failures', array('gteq' => 4))
                ->addAttributeToFilter('entity_id', array('in' => array($failureIds)))
                ->load();
    }
}