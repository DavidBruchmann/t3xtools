<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olly
 * Date: 27.04.11
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 */
 
class TYPO3_Extension {
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var TYPO3_Extension_Meta
	 */
	protected $meta;

	public function __construct($name) {
		$this->setName($name);
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function setMeta(TYPO3_Extension_Meta $meta) {
		$this->meta = $meta;
	}

	public function getMeta() {
		return $this->meta;
	}
}
