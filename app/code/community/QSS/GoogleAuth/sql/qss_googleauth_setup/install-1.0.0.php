<?php
/**
 * Created by Q-Solutions Studio.
 * Developer: Wojciech M. Wnuk <wojtek@qsolutionsstudio.com>
 */
/* @var $this QSS_GoogleAuth_Model_Installer */
$installer = $this;

$installer->startSetup();
$installer->dbInstall();
$installer->endSetup();