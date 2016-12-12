<?php
/**
 * Created by Q-Solutions Studio.
 * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 */
class QSS_GoogleAuth_Model_Observer
{
    public function adminUserSaveBefore(Varien_Event_Observer $observer)
    {
        if ($this->_isSaveAction()) {
            $user = $observer->getEvent()->getObject();
            /* @var $user Mage_Admin_Model_User */
            $googleAuthEnabled = $this->_getRequest()->getParam('googleauth_enabled', $user->getData('googleauth_enabled'));
            $user->setData('googleauth_enabled', $googleAuthEnabled);

            if ($user->dataHasChangedFor('googleauth_enabled') && !!intval($googleAuthEnabled)) {
                $mailer = Mage::getModel('qss_googleauth/mailer');
                /* @var $mailer QSS_GoogleAuth_Model_Mailer */
                $mailer->sendSecretToUser($user);
            }
        }
    }

    protected function _isSaveAction()
    {
        return in_array(
            $this->_getFullActionName(),
            array(
                'admin_permissions_user_save',
                'admin_system_account_save'
            )
        );
    }

    /**
     * @return string
     */
    protected function _getFullActionName()
    {
        return $this->_getRequest()->getModuleName() . '_' . $this->_getRequest()->getControllerName() . '_' . $this->_getRequest()->getActionName();
    }

    /**
     * @return Mage_Core_Controller_Request_Http
     */
    protected function _getRequest()
    {
        return Mage::app()->getFrontController()->getRequest();
    }
}