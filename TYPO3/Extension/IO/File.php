<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olly
 * Date: 28.04.11
 * Time: 00:15
 * To change this template use File | Settings | File Templates.
 */
 
class TYPO3_Extension_IO_File {
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var integer
	 */
	protected $size;

	/**
	 * @var integer
	 */
	protected $modificationTime;

	/**
	 * @var boolean
	 */
	protected $executable;

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var string
	 */
	protected $hash;

	public function __construct($name, $size, $modificationTime, $executable, $content, $hash = NULL) {
		$this->setName($name);
		$this->setSize($size);
		$this->setModificationTime($modificationTime);
		$this->setExecutable($executable);
		$this->setContent($content);
		$this->setHash($hash);
	}

	public function __toArray() {
		
	}

	public function __toTree($depth) {
		echo str_repeat(' ', $depth * 2) . $this->getName() . PHP_EOL;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setSize($size) {
		$this->size = (int) $size;
	}

	public function getSize() {
		return $this->size;
	}

	public function setModificationTime($modificationTime) {
		$this->modificationTime = (int) $modificationTime;
	}

	public function getModificationTime() {
		return $this->modificationTime;
	}

	public function setExecutable($executable) {
		$this->executable = (bool) $executable;
	}

	public function getExecutable() {
		return $this->executable;
	}

	public function setContent($content) {
		$this->content = $content;
	}

	public function getContent() {
		return $this->content;
	}

	public function setHash($hash) {
		$this->hash = $hash;
	}

	public function getHash() {
		return $this->hash;
	}

	public function writeTo($directory) {
		$directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$selfFileName = $directory . $this->getName();

		file_put_contents($selfFileName, $this->getContent());
		touch($selfFileName, $this->getModificationTime());
	}
}
