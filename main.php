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

$package = TYPO3_Extension_Package::read('nothing_2.4.25.t3x');
$package->writeTo('extractionTest');

echo $package;
$package->getIO()->__toTree();