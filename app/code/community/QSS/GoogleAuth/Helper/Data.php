<?php
/**
 * Created by Q-Solutions Studio.
 * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 */

class QSS_GoogleAuth_Helper_Data extends Mage_Core_Helper_Abstract
{
    const SECRET_CONFIG_PATH = "qss_googleauth/general/secret";
    const ENABLED_CONFIG_PATH = "qss_googleauth/general/enabled";
    /**
     * @var PHPGangsta_GoogleAuthenticator
     */
    public $authenticator;

    public function __construct()
    {
        $this->authenticator = new PHPGangsta_GoogleAuthenticator();
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return Mage::getStoreConfig(self::SECRET_CONFIG_PATH);
    }

    /**
     * @param null $secret
     * @return string
     */
    public function getQRCodeUrl($secret=null)
    {
        if (is_null($secret)) {
            $secret = $this->getSecret();
        }

        return $this->authenticator->getQRCodeGoogleUrl($this->getStoreName(), $secret);
    }

    /**
     * @return mixed
     */
    public function isEnabled()
    {
        return Mage::getStoreConfig(self::ENABLED_CONFIG_PATH) && $this->getSecret();
    }

    /**
     * @return mixed
     */
    public function isEnabledInConfig()
    {
        return Mage::getStoreConfig(self::ENABLED_CONFIG_PATH);
    }

    protected function getStoreName()
    {
        return str_replace(' ', '', Mage::getStoreConfig(
            'general/store_information/name'
        )) . 'Admin';
    }
}