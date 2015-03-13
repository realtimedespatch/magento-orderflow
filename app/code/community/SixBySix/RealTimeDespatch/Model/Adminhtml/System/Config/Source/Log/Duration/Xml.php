<?php

/**
 * XML Log Duration Souce Model.
 */
class SixBySix_RealTimeDespatch_Model_Adminhtml_System_Config_Source_Log_Duration_Xml
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('1 Day')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('2 Days')),
            array('value' => 3, 'label' => Mage::helper('adminhtml')->__('3 Days')),
            array('value' => 4, 'label' => Mage::helper('adminhtml')->__('4 Days')),
            array('value' => 5, 'label' => Mage::helper('adminhtml')->__('5 Days')),
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
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
        );
    }
}