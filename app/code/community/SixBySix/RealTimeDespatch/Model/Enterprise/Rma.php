<?php

/**
 * Rma Model.
 */
class SixBySix_RealTimeDespatch_Model_Enterprise_Rma extends Enterprise_Rma_Model_Rma implements SixBySix_RealTimeDespatch_Model_Interface_Exportable
{
    const EXPORT_TYPE = 'Return';

    /**
     * @var array
     */
    protected $_authorizedItems;

    /**
     * Checks whether the RMA has been exported.
     *
     * @return boolean
     */
    public function isExported()
    {
        return (boolean) $this->getIsExported();
    }

    /**
     * {@inheritdoc]
     */
    public function getExportReference()
    {
        return $this->getOrderIncrementId();
    }

    /**
     * {@inheritdoc]
     */
    public function getExportType()
    {
        return self::EXPORT_TYPE;
    }

    /**
     * {@inheritdoc]
     */
    public function export(\DateTime $exportedAt = null)
    {
        $this->setIsExported(true)
            ->setExportFailures(0)
            ->setExportedAt($exportedAt->format('Y-m-d H:i:s'))
            ->addComment('Return exported to OrderFlow');

        return $this;
    }

    /**
     * Checks whether the RMA can be exported.
     *
     * @return bool
     */
    public function hasPendingItems()
    {
        $pendingItems = Mage::getModel('enterprise_rma/item')
            ->getCollection()
            ->addFieldToFilter('rma_entity_id', $this->getId())
            ->addFieldToFilter('status', 'pending')
            ->load();

        return count($pendingItems) > 0;
    }

    /**
     * Checks whether the RMA can be exported automatically.
     *
     * @return bool
     */
    public function isExportable()
    {
        $processedItems = Mage::getModel('enterprise_rma/item')
            ->getCollection()
            ->addFieldToFilter('rma_entity_id', $this->getId())
            ->addFieldToFilter('status', array('nin' => array('authorized', 'denied')))
            ->load();

        return count($processedItems) == 0;
    }

    /**
     * Returns the lines that are available for export.
     *
     * @return array
     */
    public function getAuthorizedItems()
    {
        if ( ! $this->_authorizedItems) {
            $this->_authorizedItems = $this->_getAuthorizedItems();
        }

        return $this->_authorizedItems;
    }

    /**
     * Returns the lines that are available for export.
     *
     * @return array
     */
    protected function _getAuthorizedItems()
    {
        return Mage::getModel('enterprise_rma/item')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('rma_entity_id', $this->getId())
            ->addFieldToFilter('status', 'authorized');
    }

    /**
     * Adds a comment to the RMA
     *
     * @param string $comment
     *
     * @return SixBySix_RealTimeDespatch_Model_Enterprise_Rma
     */
    public function addComment($comment)
    {
        $history = Mage::getModel('enterprise_rma/rma_status_history');
        $history->setRmaEntityId($this->getId())
            ->setComment($comment)
            ->setIsVisibleOnFront()
            ->setIsCustomerNotified(false)
            ->setStatus($this->getStatus())
            ->setCreatedAt(Mage::getSingleton('core/date')->gmtDate())
            ->setIsAdmin(1)
            ->save();
    }
}