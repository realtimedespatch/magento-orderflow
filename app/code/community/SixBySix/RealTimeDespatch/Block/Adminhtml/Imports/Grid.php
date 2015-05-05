<?php

/**
 * Imports Grid Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Imports_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_defaultSort = 'imported';

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

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
        $collection = Mage::getModel('realtimedespatch/import')
            ->getCollection()
            ->addFieldToFilter('main_table.type', array('eq' => $this->getOperationType()))
            ->addFieldToFilter('main_table.entity', array('eq' => $this->getEntityType()));

	    $importLinesTable = Mage::getSingleton('core/resource')->getTableName('realtimedespatch_import_lines');

	    $collection->getSelect()->join(
		    array('t2' => $importLinesTable),
            'main_table.entity_id = t2.import_id'
        );

        $collection->getSelect()->group(
            'import_id'
        );

        $this->setCollection($collection);

        Mage::dispatchEvent('adminhtml_orderflow_imports_grid_prepare_columns', array('block' => $this));

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('import_id', array(
            'header'    => Mage::helper('realtimedespatch')->__('ID'),
            'align'     =>'right',
            'width'     => '50px',
            'index'     => 'import_id',
        ));

        $this->addColumn('message_id', array(
            'header'   => Mage::helper('realtimedespatch')->__('Message ID'),
            'align'    => 'left',
            'index'    => 'message_id',
            'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Request_Id(),
        ));

        $this->addColumn('reference', array(
            'header'   => Mage::helper('realtimedespatch')->__($this->getReferenceLabel()),
            'align'    => 'left',
            'index'    => 'reference',
            'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Total_Imports(),
        ));

        $this->addColumn('successes', array(
            'header' => Mage::helper('realtimedespatch')->__('Successes'),
            'align'  =>'left',
            'index'  => 'successes',
        ));

        $this->addColumn('duplicates', array(
            'header' => Mage::helper('realtimedespatch')->__('Duplicates'),
            'align'  =>'left',
            'index'  => 'duplicates',
        ));

        $this->addColumn('failures', array(
            'header' => Mage::helper('realtimedespatch')->__('Failures'),
            'align'  =>'left',
            'index'  => 'failures',
        ));

        $this->addColumn('created', array(
            'header' => Mage::helper('realtimedespatch')->__('Imported'),
            'align'  =>'left',
            'index'  => 'created',
            'filter_index' => 'main_table.created',
            'type'   => 'datetime',
            'width'  => '150px',
        ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'    => 'getImportId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('View'),
                        'url'     => array(
                            'base'=>'*/*/view',
                        ),
                        'field' => 'id'
                    )
                ),
                'filter'   => false,
                'sortable' => false,
        ));

        Mage::dispatchEvent('adminhtml_orderflow_imports_grid_prepare_columns', array('block' => $this));

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareGrid()
    {
        parent::_prepareGrid();

        $referenceValue = $this->getColumn('reference')->getFilter()->getValue();

        if ( ! $referenceValue) {
            return Mage::getSingleton('core/session')->setEntityReferenceFilter(null);
        }

        Mage::getSingleton('core/session')->setEntityReferenceFilter($referenceValue);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getImportId()));
    }

    /**
     * {@inheritdoc}
     */
    protected function _setCollectionOrder($column)
    {
        $collection = $this->getCollection();

        if ( ! $collection) {
            return;
        }

        $columnIndex = $column->getFilterIndex() ? $column->getFilterIndex() : $column->getIndex();

        if ($columnIndex == 'created') {
            $collection->setOrder('main_table.created', strtoupper($column->getDir()));
        } else {
            $collection->setOrder($columnIndex, strtoupper($column->getDir()));
        }

        return $this;
    }
}