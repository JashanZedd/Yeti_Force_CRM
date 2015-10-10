<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ********************************************************************************** */
include_once('vtlib/Vtiger/Utils.php');

/**
 * Provides API to work with vtiger CRM Menu
 * @package vtlib
 */
class Vtiger_Menu
{

	/** ID of this menu instance */
	var $id = false;
	var $label = false;
	var $sequence = false;
	var $visible = 0;

	/**
	 * Constructor
	 */
	function __construct()
	{
		
	}

	/**
	 * Initialize this instance
	 * @param Array Map 
	 * @access private
	 */
	function initialize($valuemap)
	{
		$this->id = $valuemap[parenttabid];
		$this->label = $valuemap[parenttab_label];
		$this->sequence = $valuemap[sequence];
		$this->visible = $valuemap[visible];
	}

	/**
	 * Get instance of menu by label
	 * @param String Menu label
	 */
	static function getInstance($value)
	{
		$adb = PearDatabase::getInstance();
		$query = false;
		$instance = false;
		return $instance;
	}

	/**
	 * Helper function to log messages
	 * @param String Message to log
	 * @param Boolean true appends linebreak, false to avoid it
	 * @access private
	 */
	static function log($message, $delim = true)
	{
		Vtiger_Utils::Log($message, $delim);
	}
	
	/**
	 * Delete all menus associated with module
	 * @param Vtiger_Module Instnace of module to use
	 */
	static function deleteForModule($moduleInstance)
	{
		$db = PearDatabase::getInstance();
		$db->delete('yetiforce_menu', 'module = ?', [$moduleInstance->name]);
	}
}

?>
