<?php
/**
 * WAPRO ERP main integration file.
 *
 * @package App
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace App\Integrations;

/**
 * WAPRO ERP main integration class.
 */
class Wapro
{
	/** @var string Basic table name */
	public const TABLE_NAME = 'i_#__wapro';

	/** @var int Status inactive */
	public const STATUS_INACTIVE = 0;

	/** @var int Status active */
	public const STATUS_ACTIVE = 1;

	/**
	 * Connect to WAPRO ERP SQL Server through PDO.
	 *
	 * @param string $server
	 * @param string $database
	 * @param string $user
	 * @param string $password
	 * @param int    $port
	 *
	 * @return \App\Db
	 */
	public static function connectToDatabase(string $server, string $database, string $user, string $password, int $port): \App\Db
	{
		$port = $port ?: 1433;
		\App\Db::setConfig([
			'driverName' => 'sqlsrv',
			'dsn' => "sqlsrv:Server={$server},{$port};Database={$database};",
			'username' => $user,
			'password' => $password,
			'port' => $port,
			'charset' => 'utf8'
		], 'wapro');
		$db = \App\Db::getInstance('wapro');
		$db->open();
		return $db;
	}

	/**
	 * Verify access to the WAPRO ERP system database.
	 *
	 * @param string $server
	 * @param string $database
	 * @param string $user
	 * @param string $password
	 * @param int    $port
	 *
	 * @return array
	 */
	public static function verifyDatabaseAccess(string $server, string $database, string $user, string $password, int $port): array
	{
		try {
			$db = self::connectToDatabase($server, $database, $user, $password, $port);
			if (0 === \count($db->getSchema()->getSchemaNames())) {
				$response = [
					'status' => false,
					'message' => 'There are no schemas in the database',
				];
			} else {
				$row = (new \App\Db\Query())->from('dbo.WAPRODBSTATE')->one($db);
				$response = [
					'status' => !empty($row),
					'message' => 'No data',
				];
			}
		} catch (\yii\db\Exception $th) {
			$message = $th->getMessage();
			if ($th->errorInfo) {
				foreach ($th->errorInfo as $value) {
					if ($value && !is_numeric($value) && false === strpos($message, $value)) {
						$message .= PHP_EOL . $value;
					}
				}
			}
			$response = [
				'status' => false,
				'message' => $message,
				'code' => $th->getCode(),
			];
		}
		return $response;
	}

	/**
	 * Get synchronizers.
	 *
	 * @return Wapro\Synchronizer[]
	 */
	public static function getAllSynchronizers(): array
	{
		$synchronizers = [];
		$iterator = new \DirectoryIterator(__DIR__ . '/Wapro/Synchronizer');
		foreach ($iterator as $item) {
			if ($item->isFile() && 'php' === $item->getExtension() && $synchronizer = self::getSynchronizer($item->getBasename('.php'))) {
				$synchronizers[$item->getBasename('.php')] = $synchronizer;
			}
		}
		return $synchronizers;
	}

	/**
	 * Get synchronizer by name.
	 *
	 * @param string $name
	 *
	 * @return Wapro\Synchronizer|null
	 */
	public static function getSynchronizer(string $name): ?Wapro\Synchronizer
	{
		$className = "\\App\\Integrations\\Wapro\\Synchronizer\\{$name}";
		return class_exists($className) ? new $className() : null;
	}
}
