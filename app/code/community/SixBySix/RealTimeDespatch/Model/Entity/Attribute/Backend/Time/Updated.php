<?php

/**
 * Updated Backend Attribute Model.
 */
class SixBySix_RealTimeDespatch_Model_Entity_Attribute_Backend_Time_Updated extends Mage_Eav_Model_Entity_Attribute_Backend_Time_Updated
{
    /**
     * Ensures the Exported At Timestamp Is Correct.
     *
     * @param mixed $object
     *
     * @return \SixBySix_RealTimeDespatch_Model_Entity_Attribute_Backend_Exported
     */
    public function beforeSave($object)
    {
        parent::beforeSave($object);

        if ($object->setExportedTimestamp() === true) {
            $object->setExportedAt($object->getUpdatedAt());
        }

        return $this;
    }
}