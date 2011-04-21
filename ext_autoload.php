<?php

$extensionPath = t3lib_extMgm::extPath('t3xtools');
return array(
	'tx_t3xtools_base' => $extensionPath . 'Classes/Base.php',
	'tx_t3xtools_create' => $extensionPath . 'Classes/Create.php',
	'tx_t3xtools_extract' => $extensionPath . 'Classes/Extract.php',
);

?>