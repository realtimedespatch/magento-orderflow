<?php

/**
 * Order Model.
 */
class SixBySix_RealTimeDespatch_Model_Sales_Order extends Mage_Sales_Model_Order implements SixBySix_RealTimeDespatch_Model_Interface_Exportable
{
    /**
     * Checks whether the order has been exported.
     *
     * @return boolean
     */
    public function isExported()
    {
        return (boolean) $this->getIsExported();
    }

    /**
     * Returns the goods tax amount.
     *
     * @return float
     */
    public function getGoodsTaxAmount()
    {
        return number_format($this->getBaseSubtotalInclTax() - $this->getBaseSubtotal(), 4);
    }

    /**
     * Returns the goods tax amount.
     *
     * @return float
     */
    public function getNetTotal()
    {
        return $this->getGrandTotal() - $this->getTaxAmount();
    }

    /**
     * Returns the method of payment.
     *
     * @return string
     */
    public function getPaymentMethodTitle()
    {
        return $this->getPayment()
                    ->getMethodInstance()
                    ->getTitle();
    }

    /**
     * {@inheritdoc]
     */
    public function cancel()
    {
        if ($this->canCancel()) {
            Mage::dispatchEvent('order_cancel_before', array('order' => $this));
        }

        return parent::cancel();
    }

    /**
     * {@inheritdoc]
     */
    public function getExportReference()
    {
        return $this->getIncrementId();
    }

    /**
     * {@inheritdoc]
     */
    public function getExportType()
    {
        return 'Order';
    }

    /**
     * {@inheritdoc]
     */
    public function export()
    {
       $this->setIsExported(true)
            ->setExportFailures(0)
            ->setExported(date('Y-m-d H:i:s'))
            ->addStatusHistoryComment(
                'Order Exported to OrderFlow'
            );

        return $this;
    }
}