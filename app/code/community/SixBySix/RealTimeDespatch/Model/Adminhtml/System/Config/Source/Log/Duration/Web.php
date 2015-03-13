<?php

/**
 * Web Log Duration Souce Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Source_Log_Duration_Web
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 5,  'label' => Mage::helper('adminhtml')->__('5 Days')),
            array('value' => 10, 'label' => Mage::helper('adminhtml')->__('10 Days')),
            array('value' => 15, 'label' => Mage::helper('adminhtml')->__('15 Days')),
            array('value' => 20, 'label' => Mage::helper('adminhtml')->__('20 Days')),
            array('value' => 25, 'label' => Mage::helper('adminhtml')->__('25 Days')),
            array('value' => 30, 'label' => Mage::helper('adminhtml')->__('30 Days')),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            5  => 5,
            10 => 10,
            15 => 15,
            20 => 20,
            25 => 25,
            30 => 30,
        );
    }
}