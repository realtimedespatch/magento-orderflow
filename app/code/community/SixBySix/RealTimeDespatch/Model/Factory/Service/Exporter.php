<?php

/**
 * Exporter Service Factory.
 */
class SixBySix_RealTimeDespatch_Model_Factory_Service_Exporter
{
    const EXPORTER_PRODUCT_EXPORT = 'ProductExport';
    const EXPORTER_ORDER_EXPORT   = 'OrderExport';
    const EXPORTER_RETURN_EXPORT  = 'ReturnExport';

    /**
     * Retrieves an exporter service.
     *
     * @param string $type
     */
    public function retrieve($type)
    {
        switch ($type) {
            case self::EXPORTER_PRODUCT_EXPORT:
                return new SixBySix_RealTimeDespatch_Model_Service_Exporter_Product_Export(
                    Mage::helper('realtimedespatch/service')->getProductService()
                );
            break;
            case self::EXPORTER_ORDER_EXPORT:
                return new SixBySix_RealTimeDespatch_Model_Service_Exporter_Order_Export(
                    Mage::helper('realtimedespatch/service')->getOrderService()
                );
            break;
            case self::EXPORTER_RETURN_EXPORT:
                return new SixBySix_RealTimeDespatch_Model_Service_Exporter_Return_Export(
                    Mage::helper('realtimedespatch/service')->getReturnService()
                );
                break;
            default:
                throw new Exception('Invalid Exporter Type');
            break;
        }
    }
}