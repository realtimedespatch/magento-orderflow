<?php

/*
 * Arguably a safer / less expensive choice than an observer for pre 1.8 installations missing the
 * init_from_order_session_quote_initialized event
 *
 */
use SixBySix\RealtimeDespatch\Entity\Order;
require_once 'Mage/Adminhtml/controllers/Sales/Order/EditController.php';


class SixBySix_RealTimeDespatch_Adminhtml_Sales_Order_EditController extends Mage_Adminhtml_Sales_Order_EditController
{

    /**
     * Start edit order initialization
     */
    public function startAction()
    {
        $this->_getSession()->clear();
        $orderId = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($orderId);

        try {
            if ($order->getId()) {

                if (!$order->getIsExported()) {
                    return parent::startAction();
                }
                else {
                    $response = Mage::helper('realtimedespatch/service')->getOrderService()->cancelOrder(
                        new Order($order->getIncrementId())
                    );

                    Mage::getSingleton('adminhtml/session')->addSuccess("Cancellation success: " . $response);
                    return parent::startAction();
                }

            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError('Error cancelling order at OrderFlow: ' . $e->getMessage());
            return parent::startAction();
        }

    }

}