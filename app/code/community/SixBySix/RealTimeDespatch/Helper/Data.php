<?php

/**
 * RTD Data Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether the module is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('sixbysix_realtimedespatch/general/is_enabled');
    }

    /**
     * Returns the Admin Contact Name.
     *
     * @return string
     */
    public function getAdminName()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/general/admin_name');
    }

    /**
     * Returns the Admin Contact Email.
     *
     * @return string
     */
    public function getAdminEmail()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/general/admin_email');
    }

    /**
     * Returns the API Endpoint.
     *
     * @return string
     */
    public function getApiEndpoint()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/api_config/endpoint');
    }

    /**
     * Returns the API Username.
     *
     * @return string
     */
    public function getApiUsername()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/api_config/username');
    }

    /**
     * Returns the API Password.
     *
     * @return string
     */
    public function getApiPassword()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/api_config/password');
    }

    /**
     * Returns the API Organisation.
     *
     * @return string
     */
    public function getApiOrganisation()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/api_config/organisation');
    }

    /**
     * Returns the API Channel.
     *
     * @return string
     */
    public function getApiChannel()
    {
        return (string) Mage::getStoreConfig('sixbysix_realtimedespatch/api_config/channel');
    }
}