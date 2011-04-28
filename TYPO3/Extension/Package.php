<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olly
 * Date: 27.04.11
 * Time: 23:18
 * To change this template use File | Settings | File Templates.
 */
 
class TYPO3_Extension_Package {
	const PACKAGE_Key = 'extKey';
	const PACKAGE_Configuration = 'EM_CONF';
	const PACKAGE_Files = 'FILES';

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var TYPO3_Extension_IO
	 */
	protected $IO;

	public static function read($file) {
		$reader = new TYPO3_Extension_Package_Reader($file);
		$IO = new TYPO3_Extension_IO();

		/** @var $fileElement array */
		foreach ($reader->get(TYPO3_Extension_Package::PACKAGE_Files) as $fileElement) {
			$fileName = $fileElement[TYPO3_Extension_IO::KEY_Name];
			$filePath = dirname($fileName);

			$IO->getRootDirectory()->walk($filePath, TRUE)->addFile(
				new TYPO3_Extension_IO_File(
					basename($fileName),
					$fileElement[TYPO3_Extension_IO::KEY_Size],
					$fileElement[TYPO3_Extension_IO::KEY_ModificationTime],
					$fileElement[TYPO3_Extension_IO::KEY_Executable],
					$fileElement[TYPO3_Extension_IO::KEY_Content],
					$fileElement[TYPO3_Extension_IO::KEY_Hash]
				)
			);
		}

		$package = new TYPO3_Extension_Package($reader->get(TYPO3_Extension_Package::PACKAGE_Key));
		$package->setIO($IO);

		return $package;
	}

	public function __construct($name) {
		$this->setName($name);
	}

	public function __toString() {
		return '{{ ' . $this->getName() . ' }}' . PHP_EOL;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setIO(TYPO3_Extension_IO $IO) {
		$this->IO = $IO;
	}

	public function getIO() {
		return $this->IO;
	}

	public function writeTo($directory) {
		$this->getIO()->getRootDirectory()->writeTo($directory);
	}
}
