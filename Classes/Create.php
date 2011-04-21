<?php
/**
 * creates a t3x file out of an extension directory
 **/

include_once('Base.php');


class Tx_T3xtools_Create extends Tx_T3xtools_Base {
	protected $excludeFilePatterns = '(CVS|.svn|\..*|.*~|.*\.bak)';

	/**
	 * high level function 
	 *
	 * @param	$extkey	the name of the extension
	 * @param	$pathToExtract	the path where the extension filename should be generated
	 */
	public function createT3xFile($extensionKey, $pathToExtensionFiles, $targetDirectory = NULL) {
		$t3xData = $this->compileT3xData($extensionKey, $pathToExtensionFiles);
		if (is_array($t3xData)) {
			$t3xContent = $this->compressOutputDataFromT3xData($t3xData);
			$filename = 'T3X_' . $extensionKey . '-' . str_replace('.', '_', $t3xData['EM_CONF']['version']) . '-z-' . date('YmdHi') . '.t3x';
			if ($targetDirectory) {
				if (is_dir($targetDirectory)) {
					$fullFile = rtrim($targetDirectory, '/') . $filename;
				} else {
					$fullFile = rtrim($targetDirectory, '/');
				}
				file_put_contents($fullFile, $t3xContent);
				return TRUE;
			} else {
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $filename);
				echo $t3xContent;
				exit;
			}
		}
	}


	/**
	 * serializes and gzcompresses the data,
	 * adds some md5 hash to the file
	 * 
	 * @param array $t3xData all data needed for a .t3x file in an array
	 * @return string the content that can be written to a .t3x file
	 * @formallyknownas makeUploadDataFromarray()
	 */
	protected function compressOutputDataFromT3xData(array $t3xData) {
		$serializedData = serialize($t3xData);
		$md5sum = md5($serializedData);
		return $md5sum . ':gzcompress:' . gzcompress($serializedData);
	}


}


?>