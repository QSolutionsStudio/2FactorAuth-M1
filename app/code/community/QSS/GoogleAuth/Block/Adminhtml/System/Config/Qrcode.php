<?php
/**
 * @category    QSS
 * @package     QSS_GoogleAuth
 * @author      Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class QSS_GoogleAuth_Block_Adminhtml_System_Config_Qrcode extends Mage_Adminhtml_Block_System_Config_Form_Field
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
     * @return string
     */
    public function getQRCodeUrl()
    {
        return $this->_helper->getQRCodeUrl();
    }

    /**
     * Retrieve element HTML markup
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        if (!$this->_helper->isEnabled()) {
            return __('Code not yet generated.');
        }

        return <<<HTML
<img src="{$this->getQRCodeUrl()}"/>
<div><strong>Secret code:</strong> {$this->_getSecret()}</div>
HTML;

    }

    /**
     * @return string
     */
    protected function _getSecret()
    {
        return $this->_helper->getSecret();
    }
}