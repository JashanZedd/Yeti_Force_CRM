<?php

namespace App\Installer;

/**
 * Composer installer.
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Composer
{
	/**
	 * List of public packages.
	 *
	 * @var atring[]
	 */
	public static $publicPackage = [
		'yetiforce/csrf-magic',
		'yetiforce/debugbar'
	];

	/**
	 * Post update and post install function.
	 *
	 * @param \Composer\Script\Event $event
	 */
	public static function install(\Composer\Script\Event $event)
	{
		$event->getComposer();
		$rootDir = realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR;
		$types = ['js', 'css', 'woff', 'woff2', 'ttf'];
		foreach (static::$publicPackage as $package) {
			$src = 'vendor' . DIRECTORY_SEPARATOR . $package;
			foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
				if ($item->isFile() && in_array($item->getExtension(), $types)) {
					if (!file_exists($rootDir . $item->getPathname())) {
            if (!is_dir($rootDir . $item->getPath())) {
              mkdir($rootDir . $item->getPath(), null, true);
            }
            if (!is_writable($rootDir . $item->getPath())) {
							continue;
						}
						copy($item->getRealPath(), $rootDir . $item->getPathname());
					}
				}
			}
		}
	}
}
