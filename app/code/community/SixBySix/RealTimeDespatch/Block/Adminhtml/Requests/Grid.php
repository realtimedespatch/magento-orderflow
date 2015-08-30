<?php

/**
 * Requests Grid.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Requests_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_defaultSort = 'request_id';

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setSaveParametersInSession(true);
        $this->setId('request_id');
        $this->setIdFieldName('request_id');
        $this->setDefaultSort('request_id', 'asc');
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('realtimedespatch/request')
            ->getCollection();

	    $requestLinesTable = Mage::getSingleton('core/resource')->getTableName('realtimedespatch_request_lines');

	    $collection->getSelect()->join(
		    array('t2' => $requestLinesTable),
            'main_table.entity_id = t2.request_id'
        );

        $collection->getSelect()->group(
            't2.request_id'
        );

        $this->setCollection($collection);

        Mage::dispatchEvent('adminhtml_orderflow_requests_grid_prepare_columns', array('block' => $this));

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('request_id', array(
            'header'       => Mage::helper('realtimedespatch')->__('Request ID'),
            'align'        => 'left',
            'index'        => 'request_id',
            'filter_index' => 'request_id'
        ));

        $this->addColumn('message_id', array(
            'header'   => Mage::helper('realtimedespatch')->__('OrderFlow ID'),
            'align'    => 'left',
            'index'    => 'message_id',
            'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Request_Id(),
        ));

        $this->addColumn('import_id', array(
            'header'   => Mage::helper('realtimedespatch')->__('Import ID'),
            'align'    => 'left',
            'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Import_Id(),
        ));

        $this->addColumn('type', array(
            'header'  => Mage::helper('realtimedespatch')->__('Type'),
            'align'   => 'left',
            'index'   => 'type',
            'type'    => 'options',
            'options' => array(
                'Inventory' => Mage::helper('realtimedespatch')->__('Inventory'),
                'Shipment'  => Mage::helper('realtimedespatch')->__('Shipment')
            ),
        ));

        $this->addColumn('Received', array(
            'header'       => Mage::helper('realtimedespatch')->__('Received'),
            'align'        => 'left',
            'index'        => 'created',
            'filter_index' => 'main_table.created',
            'type'         => 'datetime',
        ));

        $this->addColumn('Processed', array(
            'header'       => Mage::helper('realtimedespatch')->__('Processed'),
            'align'        => 'left',
            'index'        => 'processed',
            'filter_index' => 'processed',
            'type'         => 'datetime',
            'renderer'     => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Processed(),
        ));

        $this->addColumn('action',
            array(
                'header' => Mage::helper('catalog')->__('Action'),
                'width' => '50px',
                'type' => 'action',
                'getter' => 'getImportId',
                'actions' => array(
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

        Mage::dispatchEvent('adminhtml_orderflow_requests_grid_prepare_columns', array('block' => $this));

        return parent::_prepareColumns();
    }

    /**
     * {@inheritdoc}
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getRequestId()));
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