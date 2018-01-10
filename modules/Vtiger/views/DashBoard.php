<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * Contributor(s): YetiForce.com
 * ********************************************************************************** */

class Vtiger_DashBoard_View extends Vtiger_Index_View
{

	public function preProcessAjax(\App\Request $request)
	{
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$sourceModule = $request->getByType('sourceModule', 2);
		if (empty($sourceModule)) {
			$sourceModule = $moduleName;
		}
		$currentDashboard = $this->getDashboardId($request);
		$dashBoardModel = Vtiger_DashBoard_Model::getInstance($moduleName);
		$dashBoardModel->set('dashboardId', $currentDashboard);
		//check profile permissions for Dashboards
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if ($permission) {
			$dashBoardModel->verifyDashboard($moduleName);
			$widgets = $dashBoardModel->getDashboards('Header');
		} else {
			$widgets = [];
		}
		$modulesWithWidget = Vtiger_DashBoard_Model::getModulesWithWidgets($sourceModule, $currentDashboard);
		$viewer->assign('CURRENT_DASHBOARD', $currentDashboard);
		$viewer->assign('DASHBOARD_TYPES', Settings_WidgetsManagement_Module_Model::getDashboardTypes());
		$viewer->assign('MODULES_WITH_WIDGET', $modulesWithWidget);
		$viewer->assign('USER_PRIVILEGES_MODEL', $userPrivilegesModel);
		$viewer->assign('MODULE_PERMISSION', $permission);
		$viewer->assign('WIDGETS', $widgets);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('SRC_MODULE_NAME', $sourceModule);
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->view('dashboards/DashBoardPreProcessAjax.tpl', $moduleName);
	}

	public function preProcess(\App\Request $request, $display = true)
	{
		parent::preProcess($request, false);
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$currentDashboard = $this->getDashboardId($request);
		$dashBoardModel = Vtiger_DashBoard_Model::getInstance($moduleName);
		$dashBoardModel->set('dashboardId', $currentDashboard);
		//check profile permissions for Dashboards
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleModel->getId());
		if ($permission) {
			$dashBoardModel->verifyDashboard($moduleName);
			$widgets = $dashBoardModel->getDashboards('Header');
		} else {
			$widgets = [];
		}

		$viewer->assign('CURRENT_DASHBOARD', $currentDashboard);
		$viewer->assign('DASHBOARD_TYPES', Settings_WidgetsManagement_Module_Model::getDashboardTypes());
		$viewer->assign('USER_PRIVILEGES_MODEL', $userPrivilegesModel);
		$viewer->assign('MODULE_PERMISSION', $permission);
		$viewer->assign('WIDGETS', $widgets);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->assign('MODULE_MODEL', $moduleModel);
		if ($display) {
			$this->preProcessDisplay($request);
		}
	}

	public function preProcessTplName(\App\Request $request)
	{
		return 'dashboards/DashBoardPreProcess.tpl';
	}

	public function process(\App\Request $request)
	{
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$currentDashboard = $this->getDashboardId($request);
		$_SESSION['DashBoard'][$moduleName]['LastDashBoardId'] = $currentDashboard;
		$dashBoardModel = Vtiger_DashBoard_Model::getInstance($moduleName);
		$dashBoardModel->set('dashboardId', $currentDashboard);
		//check profile permissions for Dashboards
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$permission = $userPrivilegesModel->hasModulePermission($moduleName);
		if ($permission) {
			$widgets = $dashBoardModel->getDashboards();
		} else {
			return;
		}
		$viewer->assign('WIDGETS', $widgets);
		$viewer->view('dashboards/DashBoardContents.tpl', $moduleName);
	}

	public function postProcess(\App\Request $request)
	{
		parent::postProcess($request);
	}

	/**
	 * Get dashboard id
	 * @param \App\Request $request
	 * @return int
	 */
	public function getDashboardId(\App\Request $request)
	{
		$dashboardId = false;
		if (!$request->isEmpty('dashboardId', true)) {
			$dashboardId = $request->getInteger('dashboardId');
		} elseif (isset($_SESSION['DashBoard'][$request->getModule()]['LastDashBoardId'])) {
			$dashboardId = $_SESSION['DashBoard'][$request->getModule()]['LastDashBoardId'];
		}
		if (!$dashboardId) {
			$dashboardId = Settings_WidgetsManagement_Module_Model::getDefaultDashboard();
		}
		$request->set('dashboardId', $dashboardId);
		return $dashboardId;
	}

	/**
	 * Function to get the list of Script models to be included
	 * @param \App\Request $request
	 * @return Vtiger_JsScript_Model[]
	 */
	public function getFooterScripts(\App\Request $request)
	{
		$moduleName = $request->getModule();
		$jsFileNames = [
			'~libraries/js/gridster/jquery.gridster.js',
			'~libraries/js/flot/jquery.flot.js',
			'~libraries/js/flot/jquery.flot.pie.js',
			'~libraries/js/flot/jquery.flot.stack.js',
			'~libraries/js/jqplot/jquery.jqplot.js',
			'~libraries/js/jqplot/plugins/jqplot.canvasTextRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.canvasAxisTickRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.pieRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.barRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.categoryAxisRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.pointLabels.js',
			'~libraries/js/jqplot/plugins/jqplot.canvasAxisLabelRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.funnelRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.donutRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.barRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.logAxisRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.enhancedLegendRenderer.js',
			'~libraries/js/jqplot/plugins/jqplot.enhancedPieLegendRenderer.js',
			'modules.Vtiger.resources.DashBoard',
			'modules.' . $moduleName . '.resources.DashBoard',
			'modules.Vtiger.resources.dashboards.Widget',
			'~libraries/fullcalendar/dist/fullcalendar.js'
		];
		return array_merge(parent::getFooterScripts($request), $this->checkAndConvertJsScripts($jsFileNames));
	}

	/**
	 * Function to get the list of Css models to be included
	 * @param \App\Request $request
	 * @return Vtiger_CssScript_Model[]
	 */
	public function getHeaderCss(\App\Request $request)
	{
		$headerCss = [
			'~libraries/js/gridster/jquery.gridster.css',
			'~libraries/js/jqplot/jquery.jqplot.css',
			'~libraries/fullcalendar/dist/fullcalendar.css',
			'~libraries/fullcalendar/dist/fullcalendarCRM.css'
		];
		return array_merge(parent::getHeaderCss($request), $this->checkAndConvertCssStyles($headerCss));
	}
}
