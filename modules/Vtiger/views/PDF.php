<?php

/**
 * Export PDF Modal View Class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Maciej Stencel <m.stencel@yetiforce.com>
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Vtiger_PDF_View extends Vtiger_BasicModal_View
{
	/**
	 * Function to check permission.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\NoPermitted
	 * @throws \App\Exceptions\NoPermittedToRecord
	 */
	public function checkPermission(App\Request $request)
	{
		$moduleName = $request->getModule();
		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserPriviligesModel->hasModuleActionPermission($moduleName, 'ExportPdf')) {
			throw new \App\Exceptions\NoPermitted('LBL_PERMISSION_DENIED', 406);
		}
		if (!$request->isEmpty('record') && !\App\Privilege::isPermitted($moduleName, 'DetailView', $request->getInteger('record'))) {
			throw new \App\Exceptions\NoPermittedToRecord('ERR_NO_PERMISSIONS_FOR_THE_RECORD', 406);
		}
	}

	public function process(App\Request $request)
	{
		$this->preProcess($request);
		$moduleName = $request->getModule();
		$recordId = $request->getInteger('record');
		$view = $request->getByType('fromview', 1);
		$allRecords = Vtiger_Mass_Action::getRecordsListFromRequest($request);
		$handlerClass = Vtiger_Loader::getComponentClassName('Model', 'PDF', $moduleName);
		$pdfModel = new $handlerClass();
		$viewer = $this->getViewer($request);
		$templates = [];
		if ($view === 'Detail') {
			$templates = $pdfModel->getActiveTemplatesForRecord($recordId, $view, $moduleName);
		} elseif ($view === 'List') {
			$templates = $pdfModel->getActiveTemplatesForModule($moduleName, $view);
		}
		$standardTemplates = [];
		$dynamicTemplates = [];
		foreach ($templates as $template) {
			if ($template->get('type') === Vtiger_PDF_Model::TEMPLATE_TYPE_STANDARD) {
				$standardTemplates[] = $template;
			} elseif ($template->get('type') === Vtiger_PDF_Model::TEMPLATE_TYPE_DYNAMIC) {
				$dynamicTemplates[] = $template;
			}
		}
		unset($templates);
		$allInventoryColumns = [];
		foreach (Vtiger_Inventory_Model::getInstance($moduleName)->getFields() as $name => $field) {
			$allInventoryColumns[$name] = $field->get('label');
		}
		$selectedInventoryColumns = $allInventoryColumns;
		if ($recordId) {
			$selectedInventoryColumns = \App\Pdf\InventoryColumns::getInventoryColumnsForRecord($recordId, $moduleName);
		}
		$viewer->assign('CAN_CHANGE_SCHEME', \App\Privilege::isPermitted($moduleName, 'RecordPdfInventory'));
		$viewer->assign('STANDARD_TEMPLATES', $standardTemplates);
		$viewer->assign('DYNAMIC_TEMPLATES', $dynamicTemplates);
		$viewer->assign('ALL_INVENTORY_COLUMNS', $allInventoryColumns);
		$viewer->assign('SELECTED_INVENTORY_COLUMNS', $selectedInventoryColumns);
		$viewer->assign('ALL_RECORDS', $allRecords);
		$viewer->assign('EXPORT_VARS', [
			'record' => $recordId,
			'fromview' => $view,
		]);
		$viewer->assign('MODULE_NAME', $moduleName);
		$viewer->view('ExportPDF.tpl', $moduleName);
		$this->postProcess($request);
	}
}
