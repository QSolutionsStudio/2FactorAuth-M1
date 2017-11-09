<?php
/**
 * @category    QSS
 * @package     QSS_GoogleAuth
 * @author      Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class QSS_GoogleAuth_Model_Installer extends Mage_Core_Model_Resource_Setup
{
    /**
     * @return $this
     */
    public function dbInstall()
    {
        $this->getConnection()->addColumn($this->getTable('admin/user'), 'googleauth_enabled', 'INT(2) NULL');

        return $this;
    }
}