<?php

/**
 * Request Line Model.
 */
class SixBySix_RealTimeDespatch_Model_Request_Line extends Mage_Core_Model_Abstract
{
    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/request_line');
    }

    /**
     * Returns the decoded request body.
     *
     * @return string
     */
    public function getBody()
    {
        return json_decode(parent::getBody());
    }

    /**
     * Returns the related request body.
     *
     * @return string
     */
    public function getRequestBody()
    {
        return Mage::getModel('realtimedespatch/request')
                    ->load($this->getRequestId())
                    ->getRequestBody();
    }

    /**
     * {@inheritdoc}
     */
    public function _beforeSave()
    {
        if ($this->getParentRequest()) {
            $this->setRequestId($this->getParentRequest()->getId());
        }

        parent::_beforeSave();
    }
}