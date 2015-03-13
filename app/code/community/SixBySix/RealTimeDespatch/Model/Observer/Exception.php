<?php

/**
 * Exception Observer.
 */
class SixBySix_RealTimeDespatch_Model_Observer_Exception
{
    const STATUS_CODE_CLIENT_EXCEPTION = 400;
    const STATUS_CODE_AUTH_EXCEPTION   = 412;
    const STATUS_CODE_SERVER_EXCEPTION = 500;

    /**
     * Handles a gateway exception.
     *
     * @param mixed $event
     *
     * @return void
     */
    public function handleException($event)
    {
        $this->_handleException(
            $event->getException(),
            $event->getEntity(),
            $event->getType()
        );

        throw $event->getException();
    }

    /**
     * Handles an authentication exception.
     *
     * @param Exception $ex
     * @param string    $entity
     * @param string    $type
     *
     * @return void
     */
    protected function _handleException($ex, $entity, $type)
    {
        $tx = Mage::getModel('core/resource_transaction');
        $tx->addObject($this->_createAdminNotification($ex, $entity, $type));

        $process = Mage::getModel('realtimedespatch/process')
                       ->getCollection()
                       ->addFieldToFilter('entity', $entity)
                       ->addFieldToFilter('type', $type)
                       ->load()
                       ->getFirstItem()
                       ->incrementFailures();

        $tx->addObject($process);

        $tx->save();
    }

    /**
     * Saves and admin notification.
     *
     * @param Exception $ex
     * @param string    $entity
     * @param string    $type
     *
     * @return void
     */
    protected function _createAdminNotification($ex, $entity, $type)
    {
        $inbox = Mage::getModel('adminnotification/inbox');

        $inbox->parse(
            array(
                array(
                    'severity'    => Mage_AdminNotification_Model_Inbox::SEVERITY_MAJOR,
                    'date_added'  => date('Y-m-d H:i:s'),
                    'title'       => 'A recent Realtime Despatch OrderFlow sync failed - ['.$entity.']['.$type.']',
                    'description' => $ex->getMessage(),
                    'url'         => '',
                    'internal'    => true
                )
            )
        );

        return $inbox;
    }
}