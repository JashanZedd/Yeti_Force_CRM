<?php
/* {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} */
chdir(__DIR__ . '/../');

require_once 'include/main/WebUI.php';
require_once 'api/webservice/Core/BaseAction.php';
require_once 'api/webservice/Core/APISession.php';
require_once 'api/webservice/API.php';

if (!in_array('webservice', $enabledServices)) {
	$apiLog = new APINoPermittedException();
	$apiLog->stop(['status' => 0, 'Encrypted' => 0, 'error' => ['message' => 'Webservice - Service is not active']]);
}
AppConfig::iniSet('error_log', $root_directory . 'cache/logs/webservice.log');

try {
	$api = new API();
	$api->preProcess();
	$api->process();
	$api->postProcess();
} catch (APIException $e) {
	
}
