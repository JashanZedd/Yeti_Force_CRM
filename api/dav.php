<?php
/* {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} */
chdir (__DIR__ . '/../');
require_once('include/ConfigUtils.php');
if(!in_array('dav',$enabledServices)){
	require_once('include/exceptions/AppException.php');
	$apiLog = new APINoPermittedException();
	$apiLog->stop('Dav - Service is not active');
}
AppConfig::iniSet('error_log',$root_directory.'cache/logs/dav.log');
$baseUri = $_SERVER['SCRIPT_NAME'];

/* Database */
$pdo = new PDO('mysql:host='.$dbconfig['db_server'].';dbname='.$dbconfig['db_name'].';charset=utf8', $dbconfig['db_username'], $dbconfig['db_password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

/*
//Mapping PHP errors to exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
set_error_handler("exception_error_handler");
*/
// Autoloader
require_once('libraries/SabreDAV/autoload.php');

// Backends 
$authBackend      = new Yeti\DAV_Auth_Backend_PDO($pdo);
$principalBackend = new Yeti\DAVACL_PrincipalBackend_PDO($pdo);
$nodes = [ new Sabre\DAVACL\PrincipalCollection($principalBackend) ];
if($enableCalDAV){
	$caldavBackend = new Yeti\CalDAV_Backend_PDO($pdo);
	$nodes[] = new Sabre\CalDAV\Principal\Collection($principalBackend);
	$nodes[] = new Sabre\CalDAV\CalendarRoot($principalBackend, $caldavBackend);
}
if($enableCardDAV){
	$carddavBackend = new Yeti\CardDAV_Backend_PDO($pdo);
	$nodes[] = new Sabre\CardDAV\AddressBookRoot($principalBackend, $carddavBackend);
}
if($enableWebDAV){
	$exData = new stdClass();
	$exData->pdo = $pdo;
	$exData->storageDir = $davStorageDir;
	$exData->historyDir = $davHistoryDir;
	$exData->rootDirectory = $root_directory;
	$exData->localStorageDir = $exData->rootDirectory . $exData->storageDir;
	$exData->localHistoryDir = $exData->rootDirectory . $exData->historyDir;
	$directory = new Yeti\WebDAV_Directory('files', $exData);
	$directory->getRootChild();
	$nodes[] = $directory;
}
// The object tree needs in turn to be passed to the server class
$server = new Yeti\DAV_Server($nodes);
$server->setBaseUri($baseUri);
$server->debugExceptions = AppConfig::debug('DAV_DEBUG_EXCEPTIONS');

// Plugins
$server->addPlugin(new Sabre\DAV\Auth\Plugin($authBackend,'YetiDAV'));
$server->addPlugin(new Sabre\DAVACL\Plugin());
$server->addPlugin(new Sabre\DAV\Sync\Plugin());

if($enableBrowser){
	$server->addPlugin(new Sabre\DAV\Browser\Plugin());
	// Automatically guess (some) contenttypes, based on extesion
	$server->addPlugin(new Sabre\DAV\Browser\GuessContentType());
}
if($enableCardDAV){//CardDav integration
	$server->addPlugin(new Sabre\CardDAV\Plugin());
}
if($enableCalDAV){//CalDAV integration
	$server->addPlugin(new Sabre\CalDAV\Plugin());
	$server->addPlugin(new Sabre\CalDAV\Subscriptions\Plugin());
	$server->addPlugin(new Sabre\CalDAV\Schedule\Plugin());
}
if(AppConfig::debug('DAV_DEBUG_PLUGIN')){
	$server->addPlugin(new Yeti\Debug());
}
// And off we go!
$server->exec();
