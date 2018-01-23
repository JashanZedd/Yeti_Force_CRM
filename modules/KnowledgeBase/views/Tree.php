<?php

/**
 * @package YetiForce.View
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Tomasz Kur <t.kur@yetiforce.com>
 */
class KnowledgeBase_Tree_View extends Vtiger_Index_View
{

	/**
	 * {@inheritDoc}
	 */
	public function process(\App\Request $request)
	{
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		$linkParams = ['MODULE' => $moduleName, 'ACTION' => $request->getByType('view')];
		$linkModels = $moduleModel->getSideBarLinks($linkParams);
		$viewer = $this->getViewer($request);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUICK_LINKS', $linkModels);
		$viewer->view('TreeHeader.tpl', $moduleName);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getFooterScripts(\App\Request $request)
	{
		$parentScriptInstances = parent::getFooterScripts($request);
		$scripts = [
			'~libraries/jstree/dist/jstree.js',
			'~libraries/datatables.net/js/jquery.dataTables.js',
			'~libraries/datatables.net-bs/js/dataTables.bootstrap.js',
		];
		$viewInstances = $this->checkAndConvertJsScripts($scripts);
		$scriptInstances = array_merge($parentScriptInstances, $viewInstances);
		return $scriptInstances;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getHeaderCss(\App\Request $request)
	{
		$parentCssInstances = parent::getHeaderCss($request);
		$cssFileNames = [
			'~libraries/jstree-bootstrap-theme/dist/themes/proton/style.css',
			'~libraries/datatables.net-bs/css/dataTables.bootstrap.css',
		];
		$modalInstances = $this->checkAndConvertCssStyles($cssFileNames);
		$cssInstances = array_merge($parentCssInstances, $modalInstances);
		return $cssInstances;
	}
}
