<?php

/**
 * MailClient delete action model class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Arkadiusz Sołek <a.solek@yetiforce.com>
 */
class Settings_MailClient_DeleteAjax_Action extends Settings_Vtiger_Delete_Action
{
	/**
	 * Process.
	 *
	 * @param \App\Request $request
	 */
	public function process(App\Request $request)
	{
		Settings_MailClient_Record_Model::getInstanceById($request->getInteger('record'))->delete();
		$response = new Vtiger_Response();
		$response->setResult(Settings_Vtiger_Module_Model::getInstance($request->getModule(false))->getDefaultUrl());
		$response->emit();
	}
}
