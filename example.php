<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olly
 * Date: 27.04.11
 * Time: 23:48
 * To change this template use File | Settings | File Templates.
 */
 
require_once('TYPO3/Bootstrap.php');
TYPO3_Bootstrap::execute();

/**
 * Import T3X File
 */
$package = TYPO3_Extension_Package::read('nothing_2.4.25.t3x');
$package->writeTo('extractionTest');

/**
 * Read existing extracted extension
 */
$extension = TYPO3_Extension::read(
	'/Users/olly/Development/vhosts/schwarzenbach-wald.de/typo3conf/ext/plupload'
);

# $extension->getConfiguration()->setVersionNumber('1.2.3');
$extension->getConfiguration()->getVersion()->increment(TYPO3_Version::PART_Patch);
$extension->getConfiguration()->updateMD5Values();
$extension->getConfiguration()->write();
