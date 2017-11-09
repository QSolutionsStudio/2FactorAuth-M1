<?php
/**
 * @category    QSS
 * @package     QSS_GoogleAuth
 * @author      Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class QSS_GoogleAuth_Model_User extends Mage_Admin_Model_User
{
    /**
     * @param string $username
     * @param string $password
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function authenticate($username, $password)
    {
        $config = Mage::getStoreConfigFlag('admin/security/use_case_sensitive_login');
        $result = false;

        try {
            Mage::dispatchEvent('admin_user_authenticate_before', array(
                'username' => $username,
                'user'     => $this
            ));
            $this->loadByUsername($username);
            $sensitive = ($config) ? $username == $this->getUsername() : true;

            if (!!$this->getData('googleauth_enabled')) {
                $postData = Mage::app()->getRequest()->getPost('login', array('code' => ''));
                $loginCode = $postData['code'];
                $helper = Mage::helper('qss_googleauth');
                /* @var $helper QSS_GoogleAuth_Helper_Data */
                $isValid = $helper->authenticator->verifyCode($helper->getSecret(), $loginCode);

                if (!$isValid) {
                    Mage::throwException($helper->__('Provided login code is invalid.'));
                }
            }

            if ($sensitive && $this->getId() && Mage::helper('core')->validateHash($password, $this->getPassword())) {
                if ($this->getIsActive() != '1') {
                    Mage::throwException(Mage::helper('adminhtml')->__('This account is inactive.'));
                }
                if (!$this->hasAssigned2Role($this->getId())) {
                    Mage::throwException(Mage::helper('adminhtml')->__('Access denied.'));
                }
                $result = true;
            }

            Mage::dispatchEvent('admin_user_authenticate_after', array(
                'username' => $username,
                'password' => $password,
                'user'     => $this,
                'result'   => $result,
            ));
        }
        catch (Mage_Core_Exception $e) {
            $this->unsetData();
            throw $e;
        }

        if (!$result) {
            $this->unsetData();
        }
        return $result;
    }
}