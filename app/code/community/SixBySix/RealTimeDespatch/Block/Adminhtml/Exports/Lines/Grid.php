<?php

/**
 * Exports Grid Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Exports_Lines_Grid extends Mage_Adminhtml_Block_Widget_Grid
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

        $this->setId('exports_lines_grid');
        $this->setSaveParametersInSession(true);
        $this->setId('export_id');
        $this->setIdFieldName('export_id');
        $this->setDefaultSort('export_id', 'desc');
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('realtimedespatch/export_line')
            ->getCollection()
            ->addFieldToFilter('export_id', $this->getExport()->getId());

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