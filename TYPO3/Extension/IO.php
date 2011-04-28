<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olly
 * Date: 28.04.11
 * Time: 00:14
 * To change this template use File | Settings | File Templates.
 */
 
class TYPO3_Extension_IO {
	const FILE_Meta = 'ext_emconf.php';
	const KEY_Name = 'name';
	const KEY_Size = 'size';
	const KEY_ModificationTime = 'mtime';
	const KEY_Executable = 'is_executable';
	const KEY_Content = 'content';
	const KEY_Hash = 'content_md5';

	/**
	 * @var
	 */
	protected $rootDirectory;

	public function __construct() {
		$this->rootDirectory = new TYPO3_Extension_IO_Directory('.');
	}

	public function __toArray() {

	}

	public function __toTree() {
		$this->getRootDirectory()->__toTree();
	}

	public function getRootDirectory() {
		return $this->rootDirectory;
	}
}
