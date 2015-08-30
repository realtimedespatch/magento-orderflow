<?php

/**
 * Requests Grid Admin Block.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Requests_Lines_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_defaultSort = 'entity_id';

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->setId('requests_lines_grid');
        $this->setSaveParametersInSession(true);
        $this->setId('entity_id');
        $this->setIdFieldName('entity_id');
        $this->setDefaultSort('entity_id', 'desc');
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('realtimedespatch/request_line')
            ->getCollection()
            ->addFieldToFilter('request_id', $this->getOrderflowRequest()->getId());

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('realtimedespatch')->__('Request Line ID'),
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

        $this->addColumn('body', array(
            'header' => Mage::helper('realtimedespatch')->__('Body'),
            'align'  => 'left',
            'index'  => 'body',
            'width'  => '200px',
        ));

        $this->addColumn('processed', array(
            'header'   => Mage::helper('realtimedespatch')->__('Processed'),
            'align'    =>'left',
            'width'    => '150px',
            'index'    => 'processed',
            'type'     => 'datetime',
            'renderer' => new SixBySix_RealTimeDespatch_Block_Adminhtml_Renderer_Processed(),
        ));

        Mage::dispatchEvent('adminhtml_orderflow_request_lines_grid_prepare_columns', array('block' => $this));

        return parent::_prepareColumns();
    }
}