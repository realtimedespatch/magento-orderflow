<?php

/**
 * Export Type Model.
 */
abstract class SixBySix_RealTimeDespatch_Model_Export_Type extends Mage_Core_Model_Abstract
{
    /**
     * Updates the entities associated with the export.
     *
     * @param mixed     $lines
     * @param \DateTime $exportedAt
     *
     * @return void
     */
    public abstract function updateEntities($lines, \DateTime $exportedAt);

    /**
     * Sends the export failure emails following export four failures.
     *
     * @param array $failureIds
     *
     * @return void
     */
    protected function _sendFailureEmails($entities)
    {
        foreach ($entities as $entity) {
            $this->_sendFailureEmail($entity);
        }
    }

    /**
     * Sends an individual export failure email.
     *
     * @param SixBySix_RealTimeDespatch_Model_Interface_Exportable $entity
     *
     * @return void
     */
    protected function _sendFailureEmail(SixBySix_RealTimeDespatch_Model_Interface_Exportable $entity)
    {
        $body  = "Dear Admin. ".$entity->getExportType()." ".$entity->getExportReference();
        $body .= " has failed to export multiple times, and has been prevent from exporting.";
        $body .= " Please login to your Magento instance to find out additional information, and resolve the problem.";

        $mail = Mage::getModel('core/email');

        $mail->setToName(Mage::helper('realtimedespatch')->getAdminName());
        $mail->setToEmail(Mage::helper('realtimedespatch')->getAdminEmail());

        $mail->setSubject('Failed Order Flow Export Process - '.$entity->getExportType()." ".$entity->getExportReference());
        $mail->setBody($body);

        $mail->setFromEmail(Mage::getStoreConfig('trans_email/ident_general/email'));
        $mail->setFromName(Mage::getStoreConfig('trans_email/ident_general/name'));

        $mail->setType('text');

        try
        {
            $mail->send();
        }
        catch (Exception $ex)
        {
            Mage::log('Failed to send export failure email: '.$ex->getMessage(), null, 'orderflow.log');
        }
    }
}