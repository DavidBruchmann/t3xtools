<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olly
 * Date: 28.04.11
 * Time: 00:15
 * To change this template use File | Settings | File Templates.
 */
 
class TYPO3_Extension_IO_Directory {
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var array
	 */
	protected $files = array();

	/**
	 * @var array
	 */
	protected $directories = array();

	public function __construct($name) {
		$this->setName($name);
	}

	public function __toTree($depth = 0) {
		echo str_repeat(' ', $depth * 2) . '[' . $this->getName() . ']' . PHP_EOL;

		/** @var $directory TYPO3_Extension_IO_Directory */
		foreach ($this->directories as $directory) {
			$directory->__toTree($depth + 1);
		}

		/** @var $file TYPO3_Extension_IO_File */
		foreach ($this->files as $file) {
			$file->__toTree($depth + 1);
		}

	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function addFile(TYPO3_Extension_IO_File $file) {
		$this->files[$file->getName()] = $file;
	}

	public function addDirectory(TYPO3_Extension_IO_Directory $directory) {
		$this->directories[$directory->getName()] = $directory;
	}

	public function hasDirectory($name) {
		return (isset($this->directories[$name]));
	}

	/**
	 * @param string $name
	 * @return TYPO3_Extension_IO_Directory
	 */
	public function getDirectory($name) {
		$directory = NULL;

		if ($this->hasDirectory($name)) {
			$directory = $this->directories[$name];
		}

		return $directory;
	}

	/**
	 * @throws RuntimeException
	 * @param string $path
	 * @param boolean $create
	 * @return TYPO3_Extension_IO_Directory
	 */
	public function walk($path, $create = FALSE) {
		$directory = $this;

		if (empty($path) === FALSE && $path !== '.') {
			$pathSegments = explode(DIRECTORY_SEPARATOR, $path);

			foreach ($pathSegments as $pathSegment) {
				if ($directory->hasDirectory($pathSegment)) {
					$directory = $directory->getDirectory($pathSegment);
				} elseif ($create) {
					$directory->addDirectory(new TYPO3_Extension_IO_Directory($pathSegment));
					$directory = $directory->getDirectory($pathSegment);
				} else {
					throw new RuntimeException('Directory "' . $pathSegment . '" not found.');
				}
			}
		}

		return $directory;
	}

	public function writeTo($directory) {
		$directory = $this->sanitize($directory);
		$selfDirectory = $this->sanitize($directory . $this->getName());

		if (is_dir($selfDirectory) === FALSE) {
			// @todo Defined chmod
			mkdir($selfDirectory);
		}

		/** @var $file TYPO3_Extension_IO_File */
		foreach ($this->files as $file) {
			$file->writeTo($selfDirectory);
		}

		/** @var $directory TYPO3_Extension_IO_Directory */
		foreach ($this->directories as $directory) {
			$directory->writeTo($selfDirectory);
		}
	}

	protected function sanitize($directory) {
		$directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$directory = preg_replace('#\./#', '', $directory);
		return $directory;
	}
}
