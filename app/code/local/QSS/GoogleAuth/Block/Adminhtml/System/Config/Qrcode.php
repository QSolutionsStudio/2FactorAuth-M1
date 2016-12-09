<?php
/**
 * Created by Q-Solutions Studio.
 * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 */

class QSS_GoogleAuth_Block_Adminhtml_System_Config_Qrcode extends Mage_Adminhtml_Block_System_Config_Form_Field
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

    /**
     * @return string
     */
    public function getQRCodeUrl()
    {
        return $this->helper->getQRCodeUrl();
    }

    /**
     * Retrieve element HTML markup
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        if (!$this->helper->isEnabled()) {
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
        return $this->helper->getSecret();
    }
}