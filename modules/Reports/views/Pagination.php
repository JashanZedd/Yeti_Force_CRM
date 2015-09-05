<?php
/* {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} */
class Reports_Pagination_View extends Vtiger_IndexAjax_View
{

	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('getPagination');
	}

	public function getPagination(Vtiger_Request $request)
	{
		parent::preProcess($request, false);

		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);

		$folders = $moduleModel->getFolders();
		$listViewModel = new Reports_ListView_Model();
		$listViewModel->set('module', $moduleModel);

		$folderId = $request->get('viewname');
		if (empty($folderId) || $folderId == 'undefined') {
			$folderId = 'All';
		}
		$sortBy = $request->get('sortorder');
		$orderBy = $request->get('orderby');

		$listViewModel->set('folderid', $folderId);
		$listViewModel->set('orderby', $orderBy);
		$listViewModel->set('sortorder', $sortBy);

		$linkModels = $listViewModel->getListViewLinks();
		$pageNumber = $request->get('page');
		$listViewMassActionModels = $listViewModel->getListViewMassActions();

		if (empty($pageNumber)) {
			$pageNumber = '1';
		}
		$pagingModel = new Vtiger_Paging_Model();
		$pagingModel->set('page', $pageNumber);
		$viewer->assign('PAGING_MODEL', $pagingModel);

		if (!$this->listViewHeaders) {
			$this->listViewHeaders = $listViewModel->getListViewHeaders();
		}
		if (!$this->listViewEntries) {
			$this->listViewEntries = $listViewModel->getListViewEntries($pagingModel);
		}

		$noOfEntries = count($this->listViewEntries);

		$viewer->assign('LISTVIEW_LINKS', $linkModels);
		$viewer->assign('FOLDERS', $folders);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('VIEWNAME', $folderId);
		$viewer->assign('PAGE_NUMBER', $pageNumber);
		$viewer->assign('LISTVIEW_MASSACTIONS', $listViewMassActionModels);
		$viewer->assign('LISTVIEW_ENTRIES_COUNT', $noOfEntries);


		if (!$this->listViewCount) {
			$this->listViewCount = $listViewModel->getListViewCount();
		}
		$totalCount = $this->listViewCount;
		$pageLimit = $pagingModel->getPageLimit();
		$pageCount = ceil((int) $totalCount / (int) $pageLimit);

		if ($pageCount == 0) {
			$pageCount = 1;
		}
		$startPaginFrom = $pageNumber - 2;
		if($pageNumber == $totalCount && 1 !=  $pageNumber)
			$startPaginFrom = $pageNumber - 4;
		if($startPaginFrom <= 0 || 1 ==  $pageNumber)
			$startPaginFrom = 1;
		
		$viewer->assign('PAGE_COUNT', $pageCount);
		$viewer->assign('LISTVIEW_COUNT', $totalCount);
		$viewer->assign('START_PAGIN_FROM', $startPaginFrom);
		$viewer->assign('PAGING_MODEL', $pagingModel);
		echo $viewer->view('Pagination.tpl', $moduleName, true);
	}

	public function transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel)
	{
		return Vtiger_Util_Helper::transferListSearchParamsToFilterCondition($listSearchParams, $moduleModel);
	}
}
