<?php

$targetPath = '/Users/benni/Desktop/t3xtools/';
if (!is_dir($targetPath)) {
	mkdir($targetPath);
}

// called like this
// http://localhost/trunk/index.php?eID=t3xtools&type=create


if (t3lib_div::_GP('type') == 'extract') {
	$t3xFile = PATH_site . 'fileadmin/T3X_b13_unsubscribe-0_0_0-z-201012221446.t3x';
	$extractObj = t3lib_div::makeInstance('Tx_T3xtools_Extract');
	$res = $extractObj->unpackT3xFile($t3xFile, $targetPath);
}

if (t3lib_div::_GP('type') == 'create') {
	$extensionPath = '/Users/benni/Sites/toolbox_utf8/';
	$extensionKey = 'toolbox_utf8';
	$targetPath .= 'toolbox_utf8.t3x';
	$createObj = t3lib_div::makeInstance('Tx_T3xtools_Create');
	$res = $createObj->createT3xFile($extensionKey, $extensionPath, $targetPath);
}


// 1. T3X File von typo3.org runterladen
// 2. RPM draus machen

// Bei RPM-Installation / Update
// 1. Extract T3X in Zielpfad



exit;

?>