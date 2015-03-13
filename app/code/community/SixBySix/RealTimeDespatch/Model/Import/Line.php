<?php

/**
 * Import Line Model.
 */
class SixBySix_RealTimeDespatch_Model_Import_Line extends Mage_Core_Model_Abstract
{
    /* Line Types */
    const TYPE_SUCCESS   = 'Success';
    const TYPE_DUPLICATE = 'Duplicate';
    const TYPE_FAILURE   = 'Failure';

    /* Line Operations */
    const OP_INSERT = 'Insert';
    const OP_MERGE  = 'Merge';
    const OP_UPDATE = 'Update';

    /**
     * Constructor.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('realtimedespatch/import_line');
    }

    /**
     * Returns the entity instance for the report line.
     *
     * @return mixed
     */
    public function getEntityInstance()
    {
        return Mage::getModel('realtimedespatch/factory_entity')->retrieve(
            $this->getEntity(),
            $this->getReference()
        );
    }

    /**
     * Checks whether this is a successful import line.
     *
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->getType() == self::TYPE_SUCCESS;
    }

    /**
     * Checks whether this is a duplicate import line.
     *
     * @return boolean
     */
    public function isDuplicate()
    {
        return $this->getType() == self::TYPE_DUPLICATE;
    }

    /**
     * Checks whether this is a failed import line.
     *
     * @return boolean
     */
    public function isFailure()
    {
        return $this->getType() == self::TYPE_FAILURE;
    }

    /**
     * Checks whether this is an insert line.
     *
     * @return boolean
     */
    public function isInsert()
    {
        return $this->getOperation() == self::OP_INSERT;
    }

    /**
     * Checks whether this is a merge line.
     *
     * @return boolean
     */
    public function isMerge()
    {
        return $this->getOperation() == self::OP_MERGE;
    }

    /**
     * Checks whether this is an update line.
     *
     * @return boolean
     */
    public function isUpdate()
    {
        return $this->getOperation() == self::OP_UPDATE;
    }

    /**
     * {@inheritdoc}
     */
    public function _beforeSave()
    {
        $this->setImportId($this->getImport()->getId());

        parent::_beforeSave();
    }
}