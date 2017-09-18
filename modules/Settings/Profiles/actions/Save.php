<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class Settings_Profiles_Save_Action extends Settings_Vtiger_Basic_Action
{

	public function process(\App\Request $request)
	{
		if (!$request->isEmpty('record', true)) {
			$recordModel = Settings_Profiles_Record_Model::getInstanceById($request->getInteger('record'));
		} else {
			$recordModel = new Settings_Profiles_Record_Model();
		}
		if ($recordModel) {
			$recordModel->set('profilename', $request->get('profilename'));
			$recordModel->set('description', $request->get('description'));
			$recordModel->set('viewall', $request->get('viewall'));
			$recordModel->set('editall', $request->get('editall'));
			$recordModel->set('profile_permissions', $request->get('permissions'));
			$recordModel->save();
		}

		$redirectUrl = $recordModel->getDetailViewUrl();
		header('Location: ' . $redirectUrl);
	}

	public function validateRequest(\App\Request $request)
	{
		$request->validateWriteAccess();
	}
}
