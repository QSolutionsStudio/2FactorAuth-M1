<?php
/**
 * @category    QSS
 * @package     QSS_GoogleAuth
 * @author      Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
/* @var $this QSS_GoogleAuth_Model_Installer */
$installer = $this;

$installer->startSetup();
$installer->dbInstall();
$installer->endSetup();