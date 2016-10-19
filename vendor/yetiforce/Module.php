<?php
namespace App;

/**
 * Modules basic class
 * @package YetiForce.App
 * @license licenses/License.html
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Module
{

	protected static $moduleEntityCacheByName = [];
	protected static $moduleEntityCacheById = [];

	static public function getEntityInfo($mixed = false)
	{
		$entity = false;
		if ($mixed) {
			if (is_numeric($mixed))
				$entity = isset(static::$moduleEntityCacheById[$mixed]) ? static::$moduleEntityCacheById[$mixed] : false;
			else
				$entity = isset(static::$moduleEntityCacheByName[$mixed]) ? static::$moduleEntityCacheByName[$mixed] : false;
		}
		if (!$entity) {
			$dataReader = (new \App\Db\Query())->from('vtiger_entityname')
					->createCommand()->query();
			while ($row = $dataReader->read()) {
				$row['fieldnameArr'] = explode(',', $row['fieldname']);
				$row['searchcolumnArr'] = explode(',', $row['searchcolumn']);
				static::$moduleEntityCacheByName[$row['modulename']] = $row;
				static::$moduleEntityCacheById[$row['tabid']] = $row;
			}
			if ($mixed) {
				if (is_numeric($mixed))
					return static::$moduleEntityCacheById[$mixed];
				else
					return static::$moduleEntityCacheByName[$mixed];
			}
		}
		return $entity;
	}

	static public function getAllEntityModuleInfo($sort = false)
	{
		if (empty(static::$moduleEntityCacheById)) {
			static::getEntityInfo();
		}
		$entity = [];
		if ($sort) {
			foreach (static::$moduleEntityCacheById as $tabid => $row) {
				$entity[$row['sequence']] = $row;
			}
			ksort($entity);
		} else {
			$entity = static::$moduleEntityCacheById;
		}
		return $entity;
	}

	protected static $isModuleActiveCache = [];

	static public function isModuleActive($moduleName)
	{
		if (isset(static::$isModuleActiveCache[$moduleName])) {
			return static::$isModuleActiveCache[$moduleName];
		}
		$moduleAlwaysActive = ['Administration', 'CustomView', 'Settings', 'Users', 'Migration',
			'Utilities', 'uploads', 'Import', 'System', 'com_vtiger_workflow', 'PickList'
		];
		if (in_array($moduleName, $moduleAlwaysActive)) {
			static::$isModuleActiveCache[$moduleName] = true;
			return true;
		}
		$tabPresence = static::getTabData('tabPresence');
		$isActive = $tabPresence[static::getModuleId($moduleName)] == 0 ? true : false;
		static::$isModuleActiveCache[$moduleName] = $isActive;
		return $isActive;
	}

	protected static $tabdataCache = false;

	static public function getTabData($type)
	{
		if (static::$tabdataCache === false) {
			static::$tabdataCache = require 'user_privileges/tabdata.php';
		}
		return isset(static::$tabdataCache[$type]) ? static::$tabdataCache[$type] : false;
	}

	public static function getModuleId($name)
	{
		$tabId = static::getTabData('tabId');
		return isset($tabId[$name]) ? $tabId[$name] : false;
	}

	public static function getModuleName($tabId)
	{
		return \vtlib\Functions::getModuleName($tabId);
	}
}
