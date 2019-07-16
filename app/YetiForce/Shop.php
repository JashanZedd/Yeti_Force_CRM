<?php
/**
 * YetiForce shop file.
 *
 * @package   App
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace App\YetiForce;

/**
 * YetiForce shop class.
 */
class Shop
{
	/**
	 * Get products.
	 *
	 * @param string $state
	 *
	 * @return \App\YetiForce\Shop\AbstractBaseProduct[]
	 */
	public static function getProducts($state = 'all'): array
	{
		$config = self::getConfig();
		$products = [];
		foreach ((new \DirectoryIterator(\ROOT_DIRECTORY . '/app/YetiForce/Shop/Product')) as $item) {
			if (!$item->isDir()) {
				$fileName = $item->getBasename('.php');
				$className = "\\App\\YetiForce\\Shop\\Product\\$fileName";
				$instance = new $className($fileName);
				if ('featured' === $state && !$instance->featured) {
					continue;
				}
				if (isset($config[$fileName]) && $config[$fileName]['product'] === $fileName) {
					$instance->loadConfig($config[$fileName]);
				}
				$products[$fileName] = $instance;
			}
		}
		return $products;
	}

	/**
	 * Get variable payments.
	 *
	 * @return array
	 */
	public static function getVariablePayments(): array
	{
		return [
			'cmd' => '_xclick-subscriptions',
			'business' => 'paypal-facilitator@yetiforce.com',
			'no_shipping' => 1,
			'src' => 1,
			'sra' => 1,
			'rm' => 2,
			't3' => 'M',
			'p3' => \date('d'),
			'return' => \Config\Main::$site_URL . 'index.php?module=YetiForce&parent=Settings&view=Shop&status=success',
			'cancel_return' => \Config\Main::$site_URL . 'index.php?module=YetiForce&parent=Settings&view=Shop&status=fail',
			'notify_url' => 'https://api.yetiforce.com/shop',
			'image_url' => 'https://public.yetiforce.com/shop/logo.png',
			'custom' => \App\YetiForce\Register::getInstanceKey() . '|' . \App\YetiForce\Register::getCrmKey(),
		];
	}

	/**
	 * Get additional configuration.
	 *
	 * @return array
	 */
	public static function getConfig(): array
	{
		$rows = [];
		if (\is_dir(ROOT_DIRECTORY . '/app_data/shop/')) {
			foreach ((new \DirectoryIterator(ROOT_DIRECTORY . '/app_data/shop/')) as $item) {
				if (!$item->isDir() && 'php' === $item->getExtension()) {
					$rows[$item->getBasename('.php')] = require ROOT_DIRECTORY . '/app_data/shop/' . $item->getBasename();
				}
			}
		}
		foreach (\App\YetiForce\Register::getProducts() as  $row) {
			$rows[$row['product']] = $row;
		}
		return $rows;
	}

	/**
	 * Verification of product activity.
	 *
	 * @param string $productName
	 *
	 * @return bool
	 */
	public static function check(string $productName): bool
	{
		$productDetails = false;
		if (($products = \App\YetiForce\Register::getProducts()) && isset($products[$productName])) {
			$productDetails = $products[$productName];
		} elseif (file_exists(ROOT_DIRECTORY . "/app_data/shop/$productName.php")) {
			$productDetails = require ROOT_DIRECTORY . "/app_data/shop/$productName.php";
		}
		$status = false;
		if ($productDetails) {
			$status = self::verifyProductKey($productDetails['key']);
			if ($status) {
				$status = strtotime('now') < strtotime($productDetails['date']);
			}
			if ($status) {
				$status = \App\Company::getSize() === $productDetails['package'];
			}
		}
		return $status;
	}

	/**
	 * Get variable product.
	 *
	 * @param \App\YetiForce\Shop\AbstractBaseProduct $product
	 *
	 * @return array
	 */
	public static function getVariableProduct(Shop\AbstractBaseProduct $product): array
	{
		return [
			'a3' => $product->getPrice(),
			'item_name' => $product->name,
			'currency_code' => $product->currencyCode,
			'item_number' => 'ccc',
			'on0' => 'Package',
			'os0' => \App\Company::getSize(),
		];
	}

	/**
	 * Get paypal URL.
	 *
	 * @return string
	 */
	public static function getPaypalUrl(): string
	{
		return 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	}

	/**
	 * Verification of the product key.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public static function verifyProductKey(string $key): bool
	{
		$key = base64_decode($key);
		$l1 = substr($key, 0, 5);
		$r1 = substr($key, -2);
		$m = rtrim(ltrim($key, $l1), $r1);
		$p = substr($m, -1);
		$m = rtrim($m, $p);
		$d = substr($m, -10);
		$m = rtrim($m, $d);
		$s = substr($m, -5);
		$m = rtrim($m, $s);
		return substr(crc32($m), 2, 5) === $l1
		&& substr(sha1($d . $p), 5, 5) === $s
		&& $r1 === substr(sha1(substr(crc32($m), 2, 5) . $m . substr(sha1($d . $p), 5, 5) . $d . $p), 1, 2);
	}

	/**
	 * Verify or show a message about invalid products.
	 *
	 * @return bool
	 */
	public static function verify(): bool
	{
		foreach (\App\YetiForce\Register::getProducts() as $row) {
			if (!self::check($row['product'])) {
				return false;
			}
		}
		return true;
	}
}
