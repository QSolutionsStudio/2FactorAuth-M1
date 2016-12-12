<?php
/**
 * Created by Q-Solutions Studio.
 * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 */
class QSS_GoogleAuth_Model_Installer extends Mage_Core_Model_Resource_Setup
{
    public function dbInstall()
    {
        $this->getConnection()->addColumn($this->getTable('admin/user'), 'googleauth_enabled', 'INT(2) NULL');
    }
}