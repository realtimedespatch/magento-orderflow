<?php

/**
 * Process Schedule Controller.
 */
class SixBySix_RealTimeDespatch_Adminhtml_ProcessScheduleController extends Mage_Adminhtml_Controller_Action
{
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('realtimedespatch/schedule');
    }

    /**
     * Displays the export / import schedule.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->_title($this->__('OrderFlow'))->_title($this->__('Process Schedule'));
        $this->loadLayout();
        $this->_setActiveMenu('realtimedespatch/schedule');
        $this->renderLayout();
    }
}