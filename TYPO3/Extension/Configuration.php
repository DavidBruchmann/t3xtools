<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olly
 * Date: 01.05.11
 * Time: 23:44
 * To change this template use File | Settings | File Templates.
 */
 
class TYPO3_Extension_Configuration {
	const KEY_Title = 'title';
	const KEY_Version = 'version';
	const KEY_State = 'state';
	const KEY_Category = 'category';
	const KEY_MD5Values = '_md5_values_when_last_written';

	protected $title;

	protected $description;

	protected $category;

	protected $shy = FALSE;

	protected $internal = FALSE;

	protected $lockType;

	protected $module;

	protected $version;

	protected $state;

	protected $dependencies;

	protected $conflicts;

	protected $suggestions;

	protected $priority;

	protected $clearCacheOnLoad;

	protected $createDirectories;

	protected $uploadFolders;

	protected $modifyTables;

	protected $authorName;

	protected $authorMail;

	protected $authorCompany;

	public static function create(array $array) {
		$configuration = new TYPO3_Extension_Configuration(
			$array[self::KEY_Title],
			$array[self::KEY_Version],
			$array[self::KEY_State],
			$array[self::KEY_Category]
		);

		return $configuration;
	}

	public function __construct($title, $version, $state, $category) {
		$this->setTitle($title);
		$this->setVersion($version);
		$this->setState($state);
		$this->setCategory($category);
	}

	public function __toArray() {

	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setCategory($category) {
		$this->category = $category;
	}

	public function setShy($shy) {
		$this->shy = (bool) $shy;
	}

	public function setInternal($internal) {
		$this->internal = (bool) $internal;
	}

	public function setLockType($lockType) {
		$this->lockType = $lockType;
	}

	public function setModule($module) {
		$this->module = $module;
	}

	public function setVersion($version) {
		$this->version = $version;
	}

	public function setState($state) {
		$this->state = $state;
	}

	public function setDependencies(array $dependencies) {
		$this->dependencies = $dependencies;
	}

	public function setConflicts(array $conflicts) {
		$this->conflicts = $conflicts;
	}

	public function setSuggestions(array $suggestions) {
		$this->suggestions = $suggestions;
	}

	public function setPriority($priority) {
		$this->priority = $priority;
	}

	public function setClearCacheOnLoad($clearCacheOnLoad) {
		$this->clearCacheOnLoad = $clearCacheOnLoad;
	}

	public function setCreateDirectories($createDirectories) {
		$this->createDirectories = $createDirectories;
	}

	public function setUploadFolders($uploadFolders) {
		$this->uploadFolders = $uploadFolders;
	}

	public function setModifyTables($modifyTables) {
		$this->modifyTables = $modifyTables;
	}

	public function setAuthorName($authorName) {
		$this->authorName = $authorName;
	}

	public function setAuthorMail($authorMail) {
		$this->authorMail = $authorMail;
	}

	public function setAuthorCompany($authorCompany) {
		$this->authorCompany = $authorCompany;
	}
}
