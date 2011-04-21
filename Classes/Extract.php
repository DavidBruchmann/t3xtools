<?php
/**
 * the internals of a t3x file
 *
 * 3 parts, separated by a colon (":")
 *   - 1: md5 hash of the rest
 *   - 2: keyword "gzcompress" whether part 3 is compressed or not
 *   - 3: the content, a serialized (and possibly compressed, see "2") array
 * 
 * result of the content array (part 3):
 * an array with the following parts
 * [Array Key]
 * "extKey": a simple string
 * "EM_CONF": an associative array of the data that is stored in the file em_conf.php
 * "misc": some random info: codelines and codebytes
 * "techInfo": infos about flags (loadTCA etc) and available classes
 * "FILES": the contents of all files, as an associative array with md5 hashes
 * 
 **/
include_once('Base.php');

class Tx_T3xtools_Extract extends Tx_T3xtools_Base {
	
	protected $installType = 'L';
	
	/**
	 * high level function 
	 * @param	$filename	the full file path to the .t3x file
	 * @param	$pathToExtract	the path where the extension filename should be generated
	 */

	public function unpackT3xFile($filename, $pathToExtract) {
		$t3xdata = file_get_contents($filename);
		$pathToExtract = rtrim($pathToExtract, '/') . '/';

		if ($t3xdata === FALSE) {
			return 'The T3X file could not be fetched. Possible reasons: network problems, allow_url_fopen is off, curl is not enabled in Install tool.';
		}
		$extractedData = $this->decodeExchangeData($t3xdata);
		if (is_array($extractedData)) { // There was some data successfully transferred
			if ($extractedData['extKey'] && is_array($extractedData['FILES'])) {
				$extensionKey = $extractedData['extKey'];
				$extensionFiles = $extractedData['FILES'];
				$emConf = tx_em_Tools::fixEMCONF($extractedData['EM_CONF']);
				$extensionDirectory = $this->createDestinationDirectory($extensionKey, $pathToExtract);
				$res = $this->createExtensionContents($extensionKey, $extensionFiles, $emConf, $extensionDirectory);
			}
		}
		return $res;
	}

	/**
	 * Decodes the extension array from a binary .t3x data stream.
	 *
	 * The array consists of three parts
	 * 1: $parts[0]	the md5 of the content (part 2/3)
	 * 2: $parts[1]	keyword "gzcompress" when the file is compressed
	 * 3: $parts[2]	the actual content, a serialized content
	 *
	 *
	 * @param	string		Data stream (basically the binary result of loading the contents of a .t3x in the cache, where the content is split with a ":")
	 * @return	mixed		Array with result on success, otherwise an error string.
	 */
	protected function decodeExchangeData($t3xdata) {
		$parts = explode(':', $t3xdata, 3);
		if ($parts[1] == 'gzcompress') {
			if (function_exists('gzuncompress')) {
				$parts[2] = gzuncompress($parts[2]);
			} else {
				return 'Decoding Error: No decompressor available for compressed content. gzcompress()/gzuncompress() functions are not available!';
			}
		}
		if (md5($parts[2]) == $parts[0]) {
			$content = unserialize($parts[2]);
			if (is_array($content)) {
				return $content;
			} else {
				return 'Error: Content could not be unserialized to an array. Strange (since MD5 hashes match!)';
			}
		} else {
			return 'Error: MD5 mismatch. Maybe the extension file was downloaded and saved as a text file by the browser and thereby corrupted!? (Always select "All" filetype when saving extensions)';
		}
	}

	/**
	 * TODO: look for a better name of the function
	 * creates all the files within $extensionDirectory
	 * 
	 * @param	the extension data
	 */
	protected function createExtensionContents($extensionKey, $extensionFiles, $emConf, $extensionDirectory) {
		$errors = array();

		if ($extensionDirectory && @is_dir($extensionDirectory) && substr($extensionDirectory, -1) == '/') {

			$emConfFile = $this->constructExtEmconfFile($extensionKey, $emConf);
			$dirs = $this->extractDirsFromFileList(array_keys($extensionFiles));
			$res = $this->createDirsInPath($dirs, $extensionDirectory);
			if (!$res) {
				$writeFiles = $extensionFiles;
				$writeFiles['ext_emconf.php']['content'] = $emConfFile;
				$writeFiles['ext_emconf.php']['content_md5'] = md5($emConfFile);

					// write all files
				foreach ($writeFiles as $theFile => $fileData) {
					file_put_contents($extensionDirectory . $theFile, $fileData['content']);
					if (!@is_file($extensionDirectory . $theFile)) {
						// TODO: proper error messages
						// $errors[] = sprintf($GLOBALS['LANG']->getLL('ext_import_file_not_created'), $extensionDirectory . $theFile);
					} elseif (md5(file_get_contents($extensionDirectory . $theFile)) != $fileData['content_md5']) {
						// TODO: proper error messages
						// $errors[] = sprintf($GLOBALS['LANG']->getLL('ext_import_file_corrupted'), $extensionDirectory . $theFile);
					}
				}

				// No content, no errors. Create success output here:
				if (!count($errors)) {

						// Fix TYPO3_MOD_PATH for backend modules in extension:
					$modules = $this->trimExplode(',', $emConf['module'], TRUE);
					if (count($modules)) {
						foreach ($modules as $mD) {
							$confFileName = $extensionDirectory . $mD . '/conf.php';
							if (@is_file($confFileName)) {
								tx_em_Tools::writeTYPO3_MOD_PATH($confFileName, $this->installType, $extensionKey . '/' . $mD . '/');
							}
						}
					}
					// NOTICE: I used two hours trying to find out why a script, ext_emconf.php, written twice and in between included by PHP did not update correct the second time. Probably something with PHP-A cache and mtime-stamps.
					// But this order of the code works.... (using the empty Array with type, EMCONF and files hereunder).

					// Writing to ext_emconf.php:
					$sEMD5A = $this->createHashFromExtensionContents($extensionKey, $extensionDirectory, array('type' => $this->installType, 'EM_CONF' => array(), 'files' => array()));
					$emConf['_md5_values_when_last_written'] = serialize($sEMD5A);
					$emConfFile = $this->constructExtEmconfFile($extensionKey, $emConf);
					file_put_contents($extDirPath . 'ext_emconf.php', $emConfFile);
				}
			}
		}
		return $errors;
	}

	/**
	 * Removes the current extension of $type and creates the base folder for the new one (which is going to be imported)
	 *
	 * @param	string		the extension key
	 * @param	string		the final path without the extension name where the extension should be installed
	 * @param	boolean		If set, nothing will be deleted (neither directory nor files)
	 * @return	mixed		Returns array on success (with extension directory), otherwise an error string.
	 */
	protected function createDestinationDirectory($extensionKey, $path, $dontDelete = 0) {
		if (!$extensionKey) {
			// TODO, throw exception
			// TODO, check should be done earlier
		}

			// check if the install path is OK
		if ($path && @is_dir($path)) {
				// TODO, throw exception
			// return sprintf($GLOBALS['LANG']->getLL('clearMakeExtDir_no_dir'),
			// 	$path);
		}

			// set the extension directory
		$extensionPath = $path . $extensionKey . $suffix . '/';

			// Install dir was found, remove it then
		if (@is_dir($extensionPath)) {
			if ($dontDelete) {
				return $extensionPath;
			}
			$res = $this->rmdir($extensionPath, TRUE);
			if ($res) {
				return $res;
			}
		}

		// Create the directory
		mkdir($extensionPath);
		if (!is_dir($extensionPath)) {
			return 'Could not create directory.';
		}
		return $extensionPath;
	}


	/**
	 * Compiles the ext_emconf.php file
	 *
	 * @param	string		Extension key
	 * @param	array		EM_CONF array
	 * @return	string		PHP file content, ready to write to ext_emconf.php file
	 * @formallyknownas construct_ext_emconf_file()
	 */
	protected function constructExtEmconfFile($extensionKey, $emConf) {

		// clean version number:
		$vDat = $this->renderVersion($emConf['version']);
		$emConf['version'] = $vDat['version'];

		$code = '<?php

########################################################################
# Extension Manager/Repository config file for ext "' . $extensionKey . '".
#
# Auto generated ' . date('d-m-Y H:i') . '
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = ' . $this->arrayToCode($emConf, 0) . ';

?>';
		return str_replace(CR, '', $code);
	}



	/**
	 * Creates a MD5-hash array over the current files in the extension
	 *
	 * @param	string		Extension key
	 * @param	array		Extension information array
	 * @return	array		MD5-keys
	 * @formallyknownas serverExtensionMD5array()
	 */
	protected function createHashFromExtensionContents($extensionKey, $extensionPath, $conf) {

		// Creates upload-array - including filelist.
		$mUA = $this->compileT3xData($extensionKey, $extensionPath);

		$md5Array = array();
		if (is_array($mUA['FILES'])) {

			// Traverse files.
			foreach ($mUA['FILES'] as $fN => $d) {
				if ($fN != 'ext_emconf.php') {
					$md5Array[$fN] = substr($d['content_md5'], 0, 4);
				}
			}
		} else {
			debug(array($mUA, $conf), 'serverExtensionMD5Array:' . $extKey);
		}
		return $md5Array;
	}



}


?>