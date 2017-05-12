<?php

use \SixBySix\RealtimeDespatch\Entity\RMACollection as RtdRmaCollection;
use \SixBySix\RealtimeDespatch\Entity\RMA as RtdRma;
use SixBySix\RealtimeDespatch\Entity\RMALine as RtdRmaLine;

/**
 * Returns Mapper.
 *
 * Maps between Magento and Real Time Return Models.
 */
class SixBySix_RealTimeDespatch_Model_Mapper_Returns extends Mage_Core_Helper_Abstract
{
    /**
     * Encodes a Magento Return Collection.
     *
     * @param array     $returns
     * @param \DateTime $returnedAt
     *
     * @return RtdRmaCollection
     */
    public function encode($returns, $returnedAt)
    {
        $encodedReturns = new RtdRmaCollection;

        foreach ($returns as $return) {
            $encodedReturns[] = $this->_encodeReturn($return, $returnedAt);
        }

        return $encodedReturns;
    }

    /**
     * Encodes a single Magento Return.
     *
     * @param Enterprise_RMA_Model_Rma $return
     * @param \DateTime                $returnedAt
     *
     * @return RtdRma
     */
    protected function _encodeReturn(Enterprise_RMA_Model_Rma $return, $returnedAt)
    {
        $encodedReturn  = new RtdRma;
        $customMapping = Mage::getConfig()->getNode('rtd_mappings/return');

        foreach ($customMapping->asArray() as $magentoKey => $rtdKey) {
            $encodedReturn->setParam($rtdKey, $return->{'get'.$magentoKey}());
        }

        $encodedReturn->setParam('type', 'magento_return');
        $encodedReturn->setParam('returnDate', str_replace(' ','T', $returnedAt->format("Y-m-d H:i:s")));

        // Set references.
        $encodedReturn->setParam('authorisation', $return->getIncrementId());
        $encodedReturn->setParam('orderReference', Mage::helper('realtimedespatch/export_return')->getAuthorisationReference($return));	

        // Check for site.
        if (Mage::helper('realtimedespatch/export_return')->getSite()) {
            $encodedReturn->setParam('site', Mage::helper('realtimedespatch/export_return')->getSite());
        }

        return $encodedReturn;
    }

    /**
     * Encodes each return line.
     *
     * @param RtdRma                   $encodedReturn
     * @param Enterprise_RMA_Model_Rma $magentoReturn
     *
     * @return void
     */
    protected function _encodeReturnLines(RtdRma $encodedReturn, Enterprise_RMA_Model_Rma $magentoReturn)
    {
        $mapping = Mage::getConfig()->getNode('rtd_mappings/return_item');
        $returnItems = Mage::getSingleton('enterprise_rma/resource_item');

        $returnItems = $magentoReturn->getAuthorizedItems();

        if (count($returnItems) == 0) {
            throw new Exception('No items are available to return.');
        }

        foreach ($returnItems as $returnItem) {
            $encodedReturn->addLine($this->_encodeReturnItem($mapping, $returnItem));
        }
    }

    /**
     * Encodes an individual return item.
     *
     * @param array                     $mapping
     * @param Enterprise_Rma_Model_Item $item
     *
     * @return \SixBySix\RealtimeDespatch\Entity\ReturnLine
     */
    protected function _encodeReturnItem($mapping, Enterprise_Rma_Model_Item $item)
    {
        $returnFlowLine = new RtdRmaLine;

        foreach ($mapping->asArray() as $magentoKey => $rtdKey) {
            $returnFlowLine->setParam($rtdKey, $item->{'get'.$magentoKey}());
        }

        // Set return reason & condition.
        $returnFlowLine->setParam('reason', $item->getAttribute('reason')->getFrontend()->getValue($item));
        $returnFlowLine->setParam('condition', $item->getAttribute('condition')->getFrontend()->getValue($item));

        return $returnFlowLine;
    }
}
