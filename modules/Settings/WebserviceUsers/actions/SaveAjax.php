<?php

/**
 * Save Application.
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
class Settings_WebserviceUsers_SaveAjax_Action extends Settings_Vtiger_Save_Action
{
	/**
	 * Save.
	 *
	 * @param \App\Request $request
	 */
	public function process(\App\Request $request)
	{
		$data = $request->getMultiDimensionArray('param', [
			'server_id' => 'Integer',
			'status' => 'Integer',
			'user_name' => 'Alnum',
			'password_t' => 'Text',
			'type' => 'Integer',
			'language' => 'Text',
			'popupReferenceModule' => 'Alnum',
			'crmid' => 'Integer',
			'crmid_display' => 'Text',
			'user_id' => 'Integer'
		]);
		$typeApi = $request->getByType('typeApi', 'Alnum');
		if (!$request->isEmpty('record')) {
			$recordModel = Settings_WebserviceUsers_Record_Model::getInstanceById($request->getInteger('record'), $typeApi);
		} else {
			$recordModel = Settings_WebserviceUsers_Record_Model::getCleanInstance($typeApi);
		}
		$result = $recordModel->save($data);
		$responceToEmit = new Vtiger_Response();
		$responceToEmit->setResult($result);
		$responceToEmit->emit();
	}
}
