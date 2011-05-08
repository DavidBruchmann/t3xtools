<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Benjamin Mack <benni@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * downloads a t3x file from a TER repository and writes the .t3x file
 * on the local system
 **/
include_once('Base.php');

class Tx_T3xtools_Download extends Tx_T3xtools_Base {
	
	protected $userAgent = 'TYPO3 Extension Downloader';
	
	/**
	 * downloads a t3x file from a TER repository and writes the .t3x file
	 * on the local system
	 *
	 * @param $extensionKey	the name of the extension to fetch
	 * @param $extensionVersion the version of the extension to fetch
	 * @param $mirrorUrl the URL to the base TER repository (e.g. http://typo3.org/fileadmin/ter/)
	 * @param $targetPath the local path where the .t3x file should be created
	 * @return the absolute filename to the newly created .t3x file
	 */
	public function downloadT3xFromRepository($extensionKey, $extensionVersion, $mirrorUrl, $targetPath) {
		$extensionKey = strtolower($extensionKey);

		$mirrorUrl = rtrim($mirrorUrl, '/');
		$url = $mirrorUrl . '/' . $extensionKey{0} . '/' . $extensionKey{1} . '/' . $extensionKey . '_' . $version . '.t3x';

		$t3xFiledata = t3lib_div::getURL($url, 0, array($this->userAgent));

			// TODO: throw an exception
		if ($t3xFiledata === FALSE) {
			return 'The T3X file could not be fetched. Possible reasons: network problems, allow_url_fopen is off, curl is not enabled in Install tool.';
		}
		
		if (!$targetPath) {
			$targetPath = '/tmp';
		}
		
		$targetFile = rtrim($targetPath, '/') . '/' . basename($url); 
		file_put_contents($targetFile, $t3xFiledata);
		return $targetFile;
	}
	
}