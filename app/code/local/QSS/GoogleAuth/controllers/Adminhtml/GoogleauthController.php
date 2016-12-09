
<?php
/**
     * Created by Q-Solutions Studio.
     * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
     */

class QSS_GoogleAuth_Adminhtml_GoogleauthController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @var QSS_GoogleAuth_Helper_Data
     */
    protected $qssHelper;
    /**
     * @var Mage_Core_Model_Config
     */
    protected $config;
//    /**
//     * @var QSS_GoogleAuth_Model_Mailer
//     */
//    protected $mailer;

    public function _construct()
    {
        $this->qssHelper = Mage::helper('qss_googleauth');
        $this->config = Mage::getModel('core/config');
//        $this->mailer = $mailer;

        parent::_construct();
    }

    public function generateAction()
    {
        $newSecret = $this->qssHelper->authenticator->createSecret();
        try {
            $this->config->saveConfig(
                QSS_GoogleAuth_Helper_Data::SECRET_CONFIG_PATH,
                $newSecret,
                'default',
                0
            );

//            $this->sendNewSecret($newSecret);
        }
        catch (\Exception $e) {
            $this->_getSession()->addException($e, $e->getMessage());
        }
        $this->_getSession()->addSuccess($this->__('Your secret code has been updated.'));
    }

//    protected function sendNewSecret($newSecret)
//    {
//        $this->mailer->sendNewSecret($newSecret);
//    }
}
