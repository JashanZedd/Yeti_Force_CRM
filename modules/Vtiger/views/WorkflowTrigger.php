<?php
/*+***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 *************************************************************************************************************************************/
class Vtiger_WorkflowTrigger_View extends Vtiger_IndexAjax_View {
	public function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		if (!(Users_Privileges_Model::isPermitted($moduleName, 'WorkflowTrigger'))) {
			throw new AppException(vtranslate('LBL_PERMISSION_DENIED', $moduleName));
		}
	}

	function process (Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$record = $request->get('record');
		vimport('~~modules/com_vtiger_workflow/include.inc');
		vimport('~~modules/com_vtiger_workflow/VTEntityCache.inc');
		vimport('~~modules/com_vtiger_workflow/include.inc');
		vimport('~~include/Webservices/Utils.php');
		vimport('~~include/Webservices/Retrieve.php');
		
		$adb = PearDatabase::getInstance();
		$wfs = new VTWorkflowManager($adb);
		$workflows = $wfs->getWorkflowsForModule($moduleName, VTWorkflowManager::$MANUAL);
		
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$wsId = vtws_getWebserviceEntityId($moduleName, $record);
		$entityCache = new VTEntityCache($currentUser);
		$entityData = $entityCache->forId($wsId);
		foreach($workflows as $id=>$workflow){
			if (!$workflow->evaluate($entityCache, $entityData->getId())) {
				unset($workflows[$id]);
			}
		}

		$viewer = $this->getViewer ($request);
		$viewer->assign('RECORD', $record);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('WORKFLOWS', $workflows);
		$viewer->assign('USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->view('WorkflowTrigger.tpl', $moduleName);
	}
}
