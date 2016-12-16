<?php
/**
 * Created by Q-Solutions Studio.
 * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 */

class QSS_GoogleAuth_Model_Mailer
{
    const EMAIL_NEW_SECRET = 'qss_googleauth_email_new_secret';
    const EMAIL_USER = 'qss_googleauth_email_user';
    /**
     * @var Mage_Admin_Model_Resource_User_Collection
     */
    protected $collection;
    /**
     * @var array
     */
    protected $recipients;
    /**
     * @var Mage_Core_Model_Email_Template_Mailer
     */
    protected $coreMailer;

    public function __construct()
    {
        $this->coreMailer = Mage::getModel('core/email_template_mailer');
        $this->collection = Mage::getResourceModel('admin/user_collection');

        $this->recipients = [];

        foreach ($this->collection as $user) { /* @var $user Mage_Admin_Model_User */
            if ($user->getIsActive() && $user->getData('googleauth_enabled')) {
                $this->recipients[$user->getName()] = $user->getEmail();
            }
        }
    }

    /**
     * @param $newSecret
     */
    public function sendNewSecret($newSecret)
    {
        $this->sendSecret($newSecret, $this->getAuthSession()->getUser(), self::EMAIL_NEW_SECRET, $this->recipients);
    }

    /**
     * @param Mage_Admin_Model_User $user
     */
    public function sendSecretToUser($user)
    {
        $this->sendSecret($this->getQssHelper()->getSecret(), $user, self::EMAIL_USER, [$user->getName() => $user->getEmail()]);
    }

    /**
     * @param $secret
     * @param Mage_Admin_Model_User $user
     * @param $templateIdentifier
     * @param array $recipients
     */
    protected function sendSecret($secret, Mage_Admin_Model_User $user, $templateIdentifier, array $recipients)
    {
        if (empty($recipients)) {
            return;
        }

        $sender = [
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ];
        /** @var $emailInfo Mage_Core_Model_Email_Info */
        $emailInfo = Mage::getModel('core/email_info');
        foreach ($recipients as $name => $email) {
            $emailInfo->addTo($email, $name);
        }
        $this->coreMailer
            ->addEmailInfo($emailInfo)
            ->setTemplateId($templateIdentifier)
            ->setTemplateParams(
                    [
                        'secret' => $secret,
                        'qrcode' => $this->getQssHelper()->getQRCodeUrl($secret)
                    ]
                )
            ->setSender($sender)
            ->setStoreId(0);

        $this->coreMailer->send();
    }

    /**
     * @return QSS_GoogleAuth_Helper_Data
     */
    public function getQssHelper()
    {
        return Mage::helper('qss_googleauth');
    }

    /**
     * @return Mage_Admin_Model_Session
     */
    public function getAuthSession()
    {
        return Mage::getSingleton('admin/session');
    }
}