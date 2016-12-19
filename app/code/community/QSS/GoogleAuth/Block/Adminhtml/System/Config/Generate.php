<?php
/**
 * Created by Q-Solutions Studio.
 * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 */

class QSS_GoogleAuth_Block_Adminhtml_System_Config_Generate extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * @var QSS_GoogleAuth_Helper_Data
     */
    protected $helper;

    protected function _construct()
    {
        parent::_construct();
        $this->helper = Mage::helper('qss_googleauth');
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $generate = $this->__('Generate secret');
        $disabled = $this->helper->isEnabledInConfig() ? '' : ' disabled';
        $url = $this->getUrl('adminhtml/googleauth/generate');

        return <<<HTML
<button onclick="new Ajax.Request('$url', {onComplete: function(){location.reload()}});return false;"$disabled>$generate</button>
HTML;
    }
}