<?php
/* {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} */

class Settings_Inventory_CreditLimits_View extends Settings_Vtiger_Index_View
{

	public function getView(){
		return 'CreditLimits';
	}
	
	public function process(Vtiger_Request $request)
	{
		$mode = $request->getMode();
		if (!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
		$view = $this->getView();
		$recordModel = new Settings_Inventory_Record_Model();
		$recordModel->setType($view);
		$allData = Settings_Inventory_Record_Model::getDataAll($view);

		$qualifiedModuleName = $request->getModule(false);
		$viewer = $this->getViewer($request);
		$viewer->assign('PAGE_LABELS', $this->getPageLabels($request));
		$viewer->assign('RECORD_MODEL', $recordModel);
		$viewer->assign('INVENTORY_DATA', $allData);
		$viewer->assign('VIEW', $view);
		$viewer->assign('CURRENT_USER_MODEL', Users_Record_Model::getCurrentUserModel());
		$viewer->assign('CURRENCY', Vtiger_Util_Helper::getBaseCurrency());
		$viewer->view('Index.tpl', $qualifiedModuleName);
	}

	function getPageLabels(Vtiger_Request $request)
	{
		$qualifiedModuleName = $request->getModule(false);
		if ($request->get('type')) {
			$view = $request->get('type');
		} else {
			$view = $request->get('view');
		}
		$translations = [];
		$translations['title'] = 'LBL_' . strtoupper($view);
		$translations['title_single'] = 'LBL_' . strtoupper($view) . '_SINGLE';
		$translations['description'] = 'LBL_' . strtoupper($view) . '_DESCRIPTION';
		return $translations;
	}
}
