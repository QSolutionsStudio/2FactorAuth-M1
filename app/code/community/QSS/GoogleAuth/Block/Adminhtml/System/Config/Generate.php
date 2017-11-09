<?php
/**
 * @category    QSS
 * @package     QSS_GoogleAuth
 * @author      Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class QSS_GoogleAuth_Block_Adminhtml_System_Config_Generate extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * @var QSS_GoogleAuth_Helper_Data
     */
    protected $_helper;


    protected function _construct()
    {
        parent::_construct();
        $this->_helper = Mage::helper('qss_googleauth');
    }

    /**
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $generate = $this->__('Generate secret');
        $disabled = $this->_helper->isEnabledInConfig() ? '' : ' disabled';
        $url = $this->getUrl('adminhtml/googleauth/generate');

        return <<<HTML
<button onclick="new Ajax.Request('$url', {onComplete: function(){location.reload()}});return false;"$disabled>$generate</button>
HTML;
    }
}