<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): YetiForce.com
 * *********************************************************************************** */

class CustomView_EditAjax_View extends Vtiger_IndexAjax_View
{
	/**
	 * Function to check permission.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\NoPermitted
	 */
	public function checkPermission(\App\Request $request)
	{
		if (\App\User::getCurrentUserModel()->isAdmin()) {
			return;
		}
		if (($request->get('duplicate') !== '1') && $request->has('record') && !CustomView_Record_Model::getInstanceById($request->getInteger('record'))->isEditable()) {
			throw new \App\Exceptions\NoPermitted('LBL_PERMISSION_DENIED', 406);
		}
	}

	public function process(\App\Request $request)
	{
		$viewer = $this->getViewer($request);
		$sourceModuleName = $request->getByType('source_module', 2);
		$moduleName = $request->getModule();
		$record = $request->getInteger('record');
		if (is_numeric($sourceModuleName)) {
			$sourceModuleName = \App\Module::getModuleName($sourceModuleName);
		}
		$sourceModuleModel = Vtiger_Module_Model::getInstance($sourceModuleName);
		$recordStructureInstance = Vtiger_RecordStructure_Model::getInstanceForModule($sourceModuleModel);

		if (!empty($record)) {
			$customViewModel = CustomView_Record_Model::getInstanceById($record);
			$viewer->assign('MODE', 'edit');
		} else {
			$customViewModel = new CustomView_Record_Model();
			$customViewModel->setModule($sourceModuleName);
			$viewer->assign('MODE', '');
		}

		$viewer->assign('ADVANCE_CRITERIA', $customViewModel->transformToNewAdvancedFilter());
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('DATE_FILTERS', Vtiger_AdvancedFilter_Helper::getDateFilter($moduleName));
		// Added to show event module custom fields
		if ($sourceModuleName === 'Calendar') {
			$advanceFilterOpsByFieldType = Calendar_Field_Model::getAdvancedFilterOpsByFieldType();
		} else {
			$advanceFilterOpsByFieldType = Vtiger_Field_Model::getAdvancedFilterOpsByFieldType();
		}
		$viewer->assign('ADVANCED_FILTER_OPTIONS', \App\CustomView::ADVANCED_FILTER_OPTIONS);
		$viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', $advanceFilterOpsByFieldType);
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());
		$viewer->assign('CUSTOMVIEW_MODEL', $customViewModel);
		if (!$request->getBoolean('duplicate')) {
			$viewer->assign('RECORD_ID', $record);
		}
		$viewer->assign('QUALIFIED_MODULE', $sourceModuleName);
		$viewer->assign('SOURCE_MODULE', $sourceModuleName);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		if ($customViewModel->get('viewname') === 'All') {
			$viewer->assign('CV_PRIVATE_VALUE', App\CustomView::CV_STATUS_DEFAULT);
		} else {
			$viewer->assign('CV_PRIVATE_VALUE', App\CustomView::CV_STATUS_PRIVATE);
		}
		$viewer->assign('CV_PENDING_VALUE', App\CustomView::CV_STATUS_PENDING);
		$viewer->assign('CV_PUBLIC_VALUE', App\CustomView::CV_STATUS_PUBLIC);
		$viewer->assign('MODULE_MODEL', $sourceModuleModel);
		$viewer->view('EditView.tpl', $moduleName);
	}
}
