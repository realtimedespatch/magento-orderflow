<?php

class SixBySix_RealTimeDespatch_Block_Adminhtml_System_Config_Fieldset_Hint
    extends Mage_Adminhtml_Block_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template = 'realtimedespatch/system/config/fieldset/hint.phtml';

    /**
     * Render fieldset html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }

    public function getOrderFlowVersion()
    {
        return (string) Mage::getConfig()->getNode('modules/SixBySix_RealTimeDespatch/version');
    }

}