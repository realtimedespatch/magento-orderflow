<?php

/**
 * Imports Grid Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Imports_Lines_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_defaultSort = 'processed';

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('imports_lines_grid');
        $this->setSaveParametersInSession(true);
        $this->setId('import_id');
        $this->setIdFieldName('import_id');
        $this->setDefaultSort('import_id', 'desc');
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('realtimedespatch/import_line')
            ->getCollection()
            ->addFieldToFilter('import_id', $this->getImport()->getId());

        $this->setCollection($collection);

        if (Mage::getSingleton('core/session')->getEntityReferenceFilter()) {
            $this->setDefaultFilter(array('reference' => Mage::getSingleton('core/session')->getEntityReferenceFilter()));
            Mage::getSingleton('core/session')->setEntityReferenceFilter(null);
        }

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('realtimedespatch')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));

        $this->addColumn('sequence_id', array(
            'header' => Mage::helper('realtimedespatch')->__('Sequence ID'),
            'align'  => 'left',
            'index'  => 'sequence_id',
            'width'  => '200px',
        ));

        $this->addColumn('reference', array(
            'header' => Mage::helper('realtimedespatch')->__($this->getReferenceLabel()),
            'align'  =>'left',
            'index'  => 'reference',
            'width'  => '200px',
        ));

        $this->addColumn('type', array(
            'header'  => Mage::helper('realtimedespatch')->__('Result'),
            'align'   =>'left',
            'index'   => 'type',
            'type'    => 'options',
            'options' => array('Success' => 'Success', 'Duplicate' => 'Duplicate', 'Failure' => 'Failure'),
        ));

        $this->addColumn('message', array(
            'header'  => Mage::helper('realtimedespatch')->__('Message'),
            'align'   =>'left',
            'width'   => '500px',
            'index'   => 'message',
        ));

        $this->addColumn('processed', array(
            'header' => Mage::helper('realtimedespatch')->__('Processed'),
            'align'  =>'left',
            'width'  => '150px',
            'index'  => 'processed',
            'type'   => 'datetime',
        ));

        Mage::dispatchEvent('adminhtml_orderflow_import_lines_grid_prepare_columns', array('block' => $this));

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getRowUrl($row)
    {
        return Mage::getModel('realtimedespatch/factory_entity')->retrieveAdminUrl(
            $row->getEntity(),
            $row->getReference()
        );
    }
}