<?php

/**
 * Admin Info Helper.
 */
class SixBySix_RealTimeDespatch_Helper_Admin_Info extends Mage_Core_Helper_Abstract
{
    /**
     * Checks whether admin info panels are enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('sixbysix_realtimedespatch/admin_info/is_enabled');
    }
}