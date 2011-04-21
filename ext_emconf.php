<?php

########################################################################
# Extension Manager/Repository config file for ext "t3xtools".
#
# Auto generated 21-04-2011 15:01
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Utilities for unpacking and packing .t3x files',
	'description' => 'Simple API functions to create a t3x file out of a directory and vice versa.',
	'category' => 'fe',
	'author' => 'Benjamin Mack',
	'author_email' => 'benni@typo3.org',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:8:{s:19:"createT3XfromSVN.sh";s:4:"43e7";s:13:"createt3x.php";s:4:"7c8a";s:16:"ext_autoload.php";s:4:"e696";s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"25f2";s:16:"Classes/Base.php";s:4:"4efc";s:18:"Classes/Create.php";s:4:"f988";s:19:"Classes/Extract.php";s:4:"22c9";}',
	'suggests' => array(
	),
);

?>