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

class Vtiger_Workflow_Action extends Vtiger_Action_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->exposeMethod('execute');
	}

	function checkPermission()
	{
		return true;
	}

	public function process(Vtiger_Request $request)
	{
		$mode = $request->getMode();
		if (!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
	}

	public function execute(Vtiger_Request $request)
	{
		$moduleName = $request->getModule();
		$record = $request->get('record');
		$ids = $request->get('ids');
		Vtiger_WorkflowTrigger_Model::execute($moduleName, $record, $ids);
		$response = new Vtiger_Response();
		$response->setResult(true);
		$response->emit($taggedInfo);
	}
}
