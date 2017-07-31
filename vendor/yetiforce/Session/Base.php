<?php
namespace App\Session;

/**
 * Base Session Class
 * @package YetiForce.App
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Base extends \SessionHandler
{

	/**
	 * Is driver available
	 * @return bool
	 */
	public static function isSupported()
	{
		return true;
	}

	/**
	 * Construct
	 * @param string $name
	 * @param array $cookie
	 */
	public function __construct(string $name = 'SYTID', $cookie = [])
	{
		$cookie += [
			'lifetime' => 0,
			'path' => ini_get('session.cookie_path'),
			'domain' => ini_get('session.cookie_domain'),
			'secure' => \App\RequestUtil::getBrowserInfo()->https,
			'httponly' => true
		];
		session_name($name);
		session_set_cookie_params(
			$cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']
		);
	}

	/**
	 * Function to get the value for a given key
	 * @param string $key
	 * @return mixed Value for the given key
	 */
	public function get(string $key)
	{
		return $_SESSION[$key];
	}

	/**
	 * Function to set the value for a given key
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function set(string $key, $value)
	{
		$_SESSION[$key] = $value;
	}

	/**
	 * Function to check if the key exists.
	 * @param string $key
	 * @return bool
	 */
	public function has(string $key)
	{
		return isset($_SESSION[$key]);
	}

	/**
	 * Function to remove the value
	 * @param string $key
	 */
	public function remove(string $key)
	{
		unset($_SESSION[$key]);
	}
}
