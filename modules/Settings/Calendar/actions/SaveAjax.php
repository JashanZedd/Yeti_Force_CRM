<?php
/* +***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 * *********************************************************************************************************************************** */
class Settings_Calendar_SaveAjax_Action extends Settings_Vtiger_IndexAjax_View {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('UpdateModuleColor');
		$this->exposeMethod('UpdateModuleActiveType');
		$this->exposeMethod('UpdateUserColor');
		$this->exposeMethod('UpdateCalendarConfig');
	}

	public function UpdateModuleColor(Vtiger_Request $request) {
		$params = $request->get('params');
		Settings_Calendar_Module_Model::updateModuleColor($params);
		$response = new Vtiger_Response();
		$response->setResult(array(
			'success' => true,
			'message' => vtranslate('LBL_SAVE_COLOR', $request->getModule(false))
		));
		$response->emit();
	}

	public function UpdateModuleActiveType(Vtiger_Request $request) {
		$params = $request->get('params');
		Settings_Calendar_Module_Model::updateModuleActiveType($params);
		$response = new Vtiger_Response();
		$response->setResult(array(
			'success' => true,
			'message' => vtranslate('LBL_SAVE_ACTIVE_TYPE', $request->getModule(false))
		));
		$response->emit();
	}

	public function UpdateUserColor(Vtiger_Request $request) {
		$params = $request->get('params');
		Settings_Calendar_Module_Model::updateUserColor($params);
		$response = new Vtiger_Response();
		$response->setResult(array(
			'success' => true,
			'message' => vtranslate('LBL_SAVE_COLOR', $request->getModule(false))
		));
		$response->emit();
	}
	
	public function UpdateCalendarConfig(Vtiger_Request $request) {
		$params = $request->get('params');
		Settings_Calendar_Module_Model::updateCalendarConfig($params);
		$response = new Vtiger_Response();
		$response->setResult(array(
			'success' => true,
			'message' => vtranslate('LBL_SAVE_CHANGES', $request->getModule(false))
		));
		$response->emit();
	}
}
