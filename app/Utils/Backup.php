<?php
/**
 * Backup class.
 *
 * @package   App
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Dudek <a.dudek@yetiforce.com>
 */

namespace App\Utils;

/**
 * Backup.
 */
class Backup
{
	/**
	 * Read catalog with backup files and return catalogs and files list.
	 *
	 * @param string $catalogToRead
	 * @param string $module
	 *
	 * @throws \App\Exceptions\NoPermittedForAdmin
	 *
	 * @return array
	 */
	public static function readCatalog(string $catalogToRead, string $module)
	{
		$catalogPath = static::getBackupCatalogPath();
		$catalogToReadArray = $returnStructure = [];
		$urlDirectory = '';
		if (!empty($catalogToRead)) {
			$catalogToReadArray = explode(DIRECTORY_SEPARATOR, $catalogToRead);
			$catalogPath .= DIRECTORY_SEPARATOR . $catalogToRead;
			$urlDirectory = $catalogToRead . DIRECTORY_SEPARATOR;
		}
		if (!static::isAllowedDirectory($catalogToRead)) {
			throw new \App\Exceptions\NoPermittedForAdmin('LBL_PERMISSION_DENIED');
		}
		$catalogs = array_diff(scandir($catalogPath, SCANDIR_SORT_ASCENDING), ['.']);
		foreach ($catalogs as $element) {
			$requestUrl = "index.php?module=$module&parent=Settings&view=Index";
			if ('..' === $element) {
				if (!empty($catalogToReadArray)) {
					array_pop($catalogToReadArray);
					$parentUrl = implode(DIRECTORY_SEPARATOR, $catalogToReadArray);
					$returnStructure['manage'] = "$requestUrl&catalog=$parentUrl";
				}
			} else {
				$record['name'] = $element;
				if (is_dir($catalogPath . DIRECTORY_SEPARATOR . $element)) {
					$record['directory'] = "$requestUrl&catalog=$urlDirectory$element";
					$returnStructure['catalogs'][] = $record;
				} else {
					$record['directory'] = "$requestUrl&action=downloadFile&mode=download&file=$urlDirectory$element";
					$returnStructure['files'][] = $record;
				}
				unset($record);
			}
		}
		return $returnStructure;
	}

	/**
	 * Return catalog with backup files.
	 *
	 * @return string
	 */
	public static function getBackupCatalogPath()
	{
		$backupPath = \AppConfig::module('Backup', 'BACKUP_PATH');
		if (empty($backupPath)) {
			throw new \App\Exceptions\NoPermittedForAdmin('ERR_CONFIGURE_BEFORE_USE');
		}
		return $backupPath;
	}

	/**
	 * Check is it an allowed directory.
	 *
	 * @param string $dir
	 *
	 * @return bool
	 */
	public static function isAllowedDirectory(string $dir)
	{
		$isAllowed = true;
		$fullPath = static::getBackupCatalogPath() . DIRECTORY_SEPARATOR . $dir;
		if (!is_writable($fullPath) || !is_dir($fullPath) || is_file($fullPath) || strpos($fullPath, '../') !== false || strpos($fullPath, '..\\') !== false) {
			$isAllowed = false;
		}
		return $isAllowed;
	}

	/**
	 * Check is it an allowed file directory.
	 *
	 * @param string $dir
	 *
	 * @return bool
	 */
	public static function isAllowedFileDirectory(string $dir)
	{
		$isAllowed = true;
		$fullPath = static::getBackupCatalogPath() . DIRECTORY_SEPARATOR . $dir;
		if (!is_writable($fullPath) || is_dir($fullPath) || !is_file($fullPath) || strpos($fullPath, '../') !== false || strpos($fullPath, '..\\') !== false) {
			$isAllowed = false;
		}
		return $isAllowed;
	}
}
