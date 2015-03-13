<?php

use SixBySix\RealtimeDespatch\Entity\Order;

/**
 * Order Grid Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Order_Cancellation
{
    public function handleOrderCancellation($event)
    {
        $order = $event->getOrder();

        if ( ! $order->isExported()) {
            return;
        }

        Mage::helper('realtimedespatch/service')
            ->getOrderService()
            ->cancelOrder(new Order($order->getIncrementId()));

        $order->addStatusHistoryComment(
            'Order Cancelled via OrderFlow',
            Mage_Sales_Model_Order::STATE_CANCELED
        );
    }
}