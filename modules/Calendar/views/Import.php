<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): YetiForce Sp. z o.o
 * *********************************************************************************** */

Vtiger_Loader::includeOnce('~vendor/yetiforce/icalendar/iCalendar_rfc2445.php');
Vtiger_Loader::includeOnce('~vendor/yetiforce/icalendar/iCalendar_components.php');
Vtiger_Loader::includeOnce('~vendor/yetiforce/icalendar/iCalendar_properties.php');
Vtiger_Loader::includeOnce('~vendor/yetiforce/icalendar/iCalendar_parameters.php');
Vtiger_Loader::includeOnce('~vendor/yetiforce/icalendar/ical-parser-class.php');
Vtiger_Loader::includeOnce('~modules/Calendar/iCalLastImport.php');

class Calendar_Import_View extends Vtiger_Import_View
{
	use \App\Controller\ExposeMethod;

	/**
	 * Calendar_Import_View constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('import');
		$this->exposeMethod('importResult');
		$this->exposeMethod('undoImport');
	}

	/**
	 * {@inheritdoc}
	 */
	public function process(\App\Request $request)
	{
		parent::process($request);
	}

	/**
	 * {@inheritdoc}
	 */
	public function uploadAndParse(\App\Request $request)
	{
		$type = $request->getByType('type', \App\Purifier::TEXT);
		if ('ics' === $type || 'ical' === $type) {
			$this->importResult($request);
		} else {
			parent::uploadAndParse($request);
		}
	}

	/**
	 * Function to show result of import.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \Exception
	 */
	public function importResult(\App\Request $request)
	{
		$moduleName = $request->getModule();
		$viewer = $this->getViewer($request);
		$request->set('type', 'ics');
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if (Import_Utils_Helper::validateFileUpload($request)) {
			$result = $moduleModel->importICS(Import_Utils_Helper::getImportFilePath(\App\User::getCurrentUserModel()));
			$viewer->assign('SUCCESS_EVENTS', $result['events']);
			$viewer->assign('SKIPPED_EVENTS', $result['skipped_events']);
			$viewer->assign('SUCCESS_TASKS', $result['task']);
			$viewer->assign('SKIPPED_TASKS', $result['skipped_task']);
		} else {
			$viewer->assign('ERROR_MESSAGE', $request->get('error_message'));
		}
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->view('ImportResult.tpl', $moduleName);
	}

	/**
	 * {@inheritdoc}
	 */
	public function undoImport(\App\Request $request)
	{
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$moduleName = $request->getModule();
		$lastImport = new IcalLastImport();
		$returnValue = $lastImport->undo($moduleName, $currentUserModel->getId());
		if (!empty($returnValue)) {
			$undoStatus = true;
		} else {
			$undoStatus = false;
		}
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('VIEW', 'List');
		$viewer->assign('UNDO_STATUS', $undoStatus);
		$viewer->view('ImportFinalResult.tpl', $moduleName);
	}
}
