<?php
/**
 * @category    QSS
 * @package     QSS_GoogleAuth
 * @author      Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
require_once 'abstract.php';

class QSS_GoogleAuth_Shell extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        if (isset($this->_args['enable'])) {
            $username = $this->_args['enable'];
            $user = $this->_getUser($username);

            if ($user->getId()) {
                $user->setData('googleauth_enabled', '1');
                if ($user->dataHasChangedFor('googleauth_enabled')) {
                    $mailer = Mage::getModel('qss_googleauth/mailer');
                    /* @var $mailer QSS_GoogleAuth_Model_Mailer */
                    $mailer->sendSecretToUser($user);
                }
                $this->_save($user);
            }
            else {
                $error = <<<USER
User '{$username}' does not exist.

USER;
                die($error);
            }
        }
        elseif (isset($this->_args['disable'])) {
            $username = $this->_args['disable'];
            $user = $this->_getUser($username);

            if ($user->getId()) {
                $user->setData('googleauth_enabled', '0');
                $this->_save($user);
            }
            else {
                $error = <<<USER
User '{$username}' does not exist.

USER;
                die($error);
            }
        }
        elseif (isset($this->_args['enableall'])) {
            $users = $this->_getUserCollection();
            $mailer = Mage::getModel('qss_googleauth/mailer');
            /* @var $mailer QSS_GoogleAuth_Model_Mailer */

            foreach ($users as $user) {
                $user->setData('googleauth_enabled', '1');
                if ($user->dataHasChangedFor('googleauth_enabled')) {
                    $mailer->sendSecretToUser($user);
                }
                $this->_save($user);
            }
        }
        elseif (isset($this->_args['disableall'])) {
            $users = $this->_getUserCollection();

            foreach ($users as $user) {
                $user->setData('googleauth_enabled', '0');
                $this->_save($user);
            }

        }
        else {
            die($this->usageHelp());
        }
    }

    public function usageHelp()
    {
        return <<<USAGE
Usage:  php googleauth.php -- [options]
  enable [user]  Enable two-factor authentication for given user
  enableall      Enable two-factor authentication for all users
  disable [user] Disable two-factor authentication for given user
  disableall     Disable two-factor authentication for all users
  -h             Short alias for help
  help           This help

USAGE;

    }

    /**
     * @param $username
     * @return Mage_Admin_Model_User
     */
    protected function _getUser($username)
    {
        return Mage::getModel('admin/user')->loadByUsername($username);
    }

    /**
     * @return Mage_Admin_Model_Resource_User_Collection
     */
    protected function _getUserCollection()
    {
        return Mage::getModel('admin/user')->getCollection();
    }

    /**
     * @param Mage_Admin_Model_User $user
     */
    protected function _save($user)
    {
        if ($user->dataHasChangedFor('googleauth_enabled')) {
            $googleauthEnabled = $user->getData('googleauth_enabled');
            $this->_getConnection()->update(
                $user->getResource()->getMainTable(),
                array('googleauth_enabled' => $googleauthEnabled),
                "{$user->getIdFieldName()} = {$user->getId()}"
            );
        }
    }

    /**
     * @return Mage_Core_Model_Resource
     */
    protected function _getResource()
    {
        return Mage::getModel('core/resource');
    }

    /**
     * @return Varien_Db_Adapter_Interface
     */
    protected function _getConnection()
    {
        return $this->_getResource()->getConnection('core_write');
    }
}

$shell = new QSS_GoogleAuth_Shell();
$shell->run();