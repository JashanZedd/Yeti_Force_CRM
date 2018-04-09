<?php

namespace App\Installer;

/**
 * Get info about libraries.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Adrian Koń <a.kon@yetiforce.com>
 */
class Credits
{
	/**
	 * Information about libraries license.
	 *
	 * @var array
	 */
	public static $licenses = [];
	/**
	 * Information about forks CRM.
	 *
	 * @var array
	 */
	public static $libraries = ['Vtiger' => ['name' => 'Vtiger', 'version' => '6.4.0 rev. 14548', 'license' => 'VPL 1.1', 'homepage' => 'https://www.vtiger.com/'], 'Sugar' => ['name' => 'Sugar CRM', 'version' => '', 'license' => 'SPL', 'homepage' => 'https://www.sugarcrm.com/']];

	/**
	 * Function gets libraries from vendor.
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return array
	 */
	public static function getVendorLibraries()
	{
		$libraries = [];
		if (file_exists(ROOT_DIRECTORY . '/composer.lock')) {
			$composerLock = \App\Json::decode(file_get_contents(ROOT_DIRECTORY . '/composer.lock'), true);
			if ($composerLock && $composerLock['packages']) {
				foreach ($composerLock['packages'] as $package) {
					$libraries[$package['name']]['name'] = $package['name'];
					$libraries[$package['name']]['version'] = $package['version'];
					if (isset(static::$licenses[$package['name']])) {
						$libraries[$package['name']]['license'] = static::$licenses[$package['name']];
					} elseif (count($package['license']) > 1) {
						$libraries[$package['name']]['license'] = implode(', ', $package['license']);
						$libraries[$package['name']]['licenseError'] = true;
					} else {
						if (stripos($package['license'][0], 'or') !== false) {
							$libraries[$package['name']]['licenseError'] = true;
						}
						$libraries[$package['name']]['license'] = $package['license'][0];
					}
					$libraries[$package['name']]['homepage'] = $package['homepage'];
				}
			}
		}
		return $libraries;
	}

	/**
	 * Function gets libraries name from public_html.
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return array
	 */
	public static function getPublicLibraries()
	{
		$libraries = [];
		$dir = ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR;
		if (file_exists($dir . '.yarn-integrity')) {
			$yarnFile = \App\Json::decode(file_get_contents($dir . '.yarn-integrity'), true);
			if ($yarnFile && $yarnFile['lockfileEntries']) {
				foreach ($yarnFile['lockfileEntries'] as $nameWithVersion => $page) {
					$name = reset(explode('@', $nameWithVersion));
					$libraries[$name]['name'] = $name;
					$libraries[$name]['homepage'] = $page;
					$packageFile = $dir . $name . DIRECTORY_SEPARATOR . 'package.json';
					if (file_exists($packageFile)) {
						$packageFileContent = \App\Json::decode(file_get_contents($packageFile), true);
						$libraries[$name]['version'] = $packageFileContent['version'];
						$license = self::getLicenseForPublic($packageFileContent, $name);
						$libraries[$name]['licenseError'] = $license['error'];
						$libraries[$name]['license'] = $license['license'];
					} else {
						$libraries[$name]['packageFileMissing'] = true;
					}
				}
			}
		}
		return $libraries;
	}

	/**
	 * Function return license for public library.
	 *
	 * @param array  $license
	 * @param string $libraryName
	 *
	 * @return array
	 */
	public static function getLicenseForPublic($packageFileContent, $libraryName)
	{
		$licenseError = false;
		$returnLicense = '';
		$license = $packageFileContent['licenses'] ?? $packageFileContent['license'];
		if (isset(static::$licenses[$libraryName])) {
		} elseif (is_array($license)) {
			if (count($license[0]) > 1) {
				$returnLicense =implode(',', array_column($license, 'type'));
			} else {
				$returnLicense = implode(',', $license);
			}
			$licenseError = true;
		} else {
			if (stripos($license, 'or') !== false) {
				$licenseError = true;
			}
			$returnLicense = $license;
		}
		return ['license'=> $returnLicense, 'error' =>$licenseError];
	}

	/**
	 * Function returns information abouts libraries.
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return array
	 */
	public static function getCredits()
	{
		return ['static' => static::$libraries, 'vendor' => self::getVendorLibraries(), 'public' => self::getPublicLibraries()];
	}
}
