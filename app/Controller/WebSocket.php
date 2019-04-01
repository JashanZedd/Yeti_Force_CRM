<?php
/**
 * Web socket controller file.
 *
 * @package   Controller
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace App\Controller;

use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;

/**
 * Web socket controller class.
 */
class WebSocket
{
	/**
	 * Swoole websocket server instance.
	 *
	 * @see https://github.com/swoole/swoole-src
	 *
	 * @var \Swoole\WebSocket\Server
	 */
	public $server;
	/**
	 * Web socket container.
	 *
	 * @var string
	 */
	private $container;

	/**
	 * Connect function.
	 *
	 * @param string $host
	 * @param int    $port
	 * @param array  $settings
	 *
	 * @see https://github.com/swoole/swoole-src
	 *
	 * @return void
	 */
	public function connect(string $host = '', int $port = 9000, array $settings = [])
	{
		if (!$host) {
			$host = \Config\WebSocket::$host;
			$port = \Config\WebSocket::$port;
		}
		$this->server = new \Swoole\WebSocket\Server($host, $port);
		$this->server->set(\array_merge([
			'buffer_output_size' => 32 * 1024 * 1024,
			'pipe_buffer_size' => 1024 * 1024 * 1024,
			//'max_connection' => 1024,
			'log_file' => ROOT_DIRECTORY . \DIRECTORY_SEPARATOR . \App\Config::debug('WEBSOCKET_LOG_FILE'),
			'log_level' => \App\Config::debug('WEBSOCKET_LOG_LEVEL'),
		], $settings));
	}

	/**
	 * Requirements validation function.
	 *
	 * @throws \App\Exceptions\AppException
	 */
	private function requirementsValidation()
	{
		if (version_compare(PHP_VERSION, '7.1', '<')) {
			throw new \App\Exceptions\AppException('Wrong PHP version, recommended version >= 7.1');
		}
		if (!\App\Config::main('application_unique_key', false)) {
			throw new \App\Exceptions\AppException('CRM is not installed');
		}
		if (!class_exists('Swoole\WebSocket\Server')) {
			throw new \App\Exceptions\AppException('Swoole is not installed');
		}
	}

	/**
	 * Process function.
	 *
	 * @throws \App\Exceptions\AppException
	 */
	public function process()
	{
		$this->requirementsValidation();
		$this->connect();

		$this->server->on('start', [$this, 'onStart']);
		$this->server->on('shutdown', [$this, 'onShutdown']);

		$this->server->on('workerStart', [$this, 'onWorkerStart']);
		$this->server->on('workerStop', [$this, 'onWorkerStop']);
		$this->server->on('workerError', [$this, 'onWorkerError']);

		$this->server->on('connect', [$this, 'onConnect']);
		$this->server->on('open', [$this, 'onOpen']);
		$this->server->on('message', [$this, 'onMessage']);
		$this->server->on('close', [$this, 'onClose']);

		$this->server->start();
	}

	/**
	 * Connecting function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 * @param int                      $fd
	 * @param int                      $fromId
	 *
	 * @return void
	 */
	public function onConnect(Server $server, int $fd, int $fromId)
	{
		\App\Log::info("Connecting | fd: {$fd} | fromId: $fromId", 'WebSocket');
	}

	/**
	 * Open the connection function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 * @param \Swoole\Http\Request     $request
	 *
	 * @return void
	 */
	public function onOpen(Server $server, \Swoole\Http\Request $request)
	{
		\App\Log::info("Open the connection | fd: {$request->fd} | path: {$request->server['path_info']}", 'WebSocket');
		$this->container = substr($request->server['path_info'], 1);
		if (!\class_exists($this->getContainerClass())) {
			\App\Log::error('Web socket container does not exist: ' . $this->container, 'WebSocket');
			$server->close($request->fd);
		}
	}

	/**
	 * Message function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 * @param \Swoole\WebSocket\Frame  $frame
	 *
	 * @return void
	 */
	public function onMessage(Server $server, Frame $frame)
	{
		try {
			\App\Log::info("Request message | fd: {$frame->fd} | Content: {$frame->data}", 'WebSocket');
			$container = $this->getContainer($frame);
			if ($container->checkPermission()) {
				$container->process();
			}
		} catch (\Throwable $e) {
			\App\Log::error($e->getMessage() . PHP_EOL . $e->__toString(), 'WebSocket');
		}
	}

	/**
	 * Closing the connection function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 * @param int                      $fd
	 * @param int                      $fromId
	 *
	 * @return void
	 */
	public function onClose(Server $server, int $fd, int $fromId)
	{
		\App\Log::info("Closing the connection | fd: {$fd} | fromId: $fromId", 'WebSocket');
	}

	/**
	 * Swoole server starting function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 *
	 * @return void
	 */
	public function onStart(Server $server)
	{
		\App\Log::info('Swoole server starting', 'WebSocket');
	}

	/**
	 * Swoole server shutdown function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 *
	 * @return void
	 */
	public function onShutdown(Server $server)
	{
		\App\Log::info('Swoole server shutdown', 'WebSocket');
	}

	/**
	 * Swoole start worker function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 * @param int                      $workerId
	 *
	 * @return void
	 */
	public function onWorkerStart(Server $server, int $workerId)
	{
		\App\Log::info("Swoole worker #$workerId starting", 'WebSocket');
	}

	/**
	 * Swoole stop worker function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 * @param int                      $workerId
	 *
	 * @return void
	 */
	public function onWorkerStop(Server $server, int $workerId)
	{
		\App\Log::info("Swoole worker #$workerId stopping", 'WebSocket');
	}

	/**
	 * Swoole error worker function.
	 *
	 * @param \Swoole\WebSocket\Server $server
	 * @param int                      $workerId
	 * @param mixed                    $workerPid
	 * @param mixed                    $exitCode
	 * @param mixed                    $signalNo
	 *
	 * @return void
	 */
	public function onWorkerError(Server $server, int $workerId, $workerPid, $exitCode, $signalNo)
	{
		\App\Log::error("Swoole worker error [workerId=$workerId, workerPid=$workerPid, exitCode=$exitCode, signalNo=$signalNo]... | " . swoole_last_error(), 'WebSocket');
	}

	/**
	 * Get container class.
	 *
	 * @return string
	 */
	private function getContainerClass(): string
	{
		return "App\\Controller\\WebSocket\\{$this->container}";
	}

	/**
	 * Get container instance.
	 *
	 * @param \Swoole\WebSocket\Frame $frame
	 *
	 * @return App\Controller\WebSocket\Base
	 */
	public function getContainer(Frame $frame): WebSocket\Base
	{
		$class = $this->getContainerClass();
		return new $class($this, $frame);
	}
}
