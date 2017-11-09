
<?php
/**
 * @category    QSS
 * @package     QSS_GoogleAuth
 * @author      Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class QSS_GoogleAuth_Adminhtml_GoogleauthController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @throws Exception
     */
    public function generateAction()
    {
        $newSecret = $this->getQssHelper()->authenticator->createSecret();
        try {
            $this->getConfig()->saveConfig(
                QSS_GoogleAuth_Helper_Data::SECRET_CONFIG_PATH,
                $newSecret,
                'default',
                0
            );

            Mage::app()->getCacheInstance()->clean(['config']);

            $this->sendNewSecret($newSecret);
        }
        catch (Exception $e) {
            $this->_getSession()->addException($e, $e->getMessage());
        }
        $this->_getSession()->addSuccess($this->__('Your secret code has been updated.'));
    }

    /**
     * @param $newSecret
     */
    protected function sendNewSecret($newSecret)
    {
        $this->getMailer()->sendNewSecret($newSecret);
    }

    /**
     * @return QSS_GoogleAuth_Helper_Data
     */
    public function getQssHelper()
    {
        return Mage::helper('qss_googleauth');
    }

    /**
     * @return QSS_GoogleAuth_Model_Mailer
     */
    public function getMailer()
    {
        return Mage::getModel('qss_googleauth/mailer');
    }

    /**
     * @return Mage_Core_Model_Config
     */
    public function getConfig()
    {
        return Mage::getModel('core/config');
    }
}
