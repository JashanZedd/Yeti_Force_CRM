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

class OSSMail_getContactMail_Action extends Vtiger_Action_Controller
{

	public function checkPermission(Vtiger_Request $request)
	{
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserPriviligesModel->hasModulePermission($moduleModel->getId())) {
			throw new AppException(vtranslate($moduleName) . ' ' . vtranslate('LBL_NOT_ACCESSIBLE'));
		}
	}

	public function process(Vtiger_Request $request)
	{
		$ids = $request->get('ids');
		$mod = $request->get('mod');
		$emailFields = [];
		$EmailSearchList = OSSMailScanner_Record_Model::getEmailSearch($mod);
		$recordModel = Vtiger_Record_Model::getInstanceById($ids, $mod);
		$name = $recordModel->getName();
		foreach ($EmailSearchList as $emailField) {
			$email = $recordModel->get($emailField[5]);
			if ($email != '') {
				$fieldlabel = vtranslate($emailField[0], $emailField[3]);
				$emailFields[] = array('name' => $name, 'fieldlabel' => $fieldlabel, 'email' => $email);
			}
		}
		$response = new Vtiger_Response();
		$response->setResult($emailFields);
		$response->emit();
	}
}
