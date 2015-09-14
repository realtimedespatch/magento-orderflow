<?php

/**
 * Bulk Export View.
 */
class SixBySix_RealTimeDespatch_Block_Adminhtml_Bulk_Export_View extends Mage_Adminhtml_Block_Widget_View_Container
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_blockGroup = 'realtimedespatch';
        $this->_controller = 'adminhtml_bulk_export_view';

        parent::__construct();

        $this->_removeButton('edit');
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderText()
    {
        return Mage::helper('realtimedespatch')->__('Bulk Catalogue Export');
    }

    /**
     * {@inheritdoc}
     */
    protected function _toHtml()
    {
        $this->_setupButtons();

        $export = Mage::getModel('realtimedespatch/bulk_export')->load(
            SixBySix_RealTimeDespatch_Model_Bulk_Export::TYPE_CATALOG,
            'type'
        );

        $this->getChild('plane')
            ->setExport($export);

        return parent::_toHtml();
    }

    /**
     * Sets up the admin buttons.
     *
     * @return void
     */
    protected function _setupButtons()
    {
        $this->addButton(
            'bulk_catalogue_export',
            array(
                'label'   => Mage::helper('realtimedespatch')->__('Export Catalogue'),
                'onclick' => "confirmSetLocation('Are you sure you wish to export your entire catalogue to OrderFlow?', '{$this->getUrl('*/*/export')}')",
                'class'   => 'go'
            ),
            0,
            -1
        );
    }
}