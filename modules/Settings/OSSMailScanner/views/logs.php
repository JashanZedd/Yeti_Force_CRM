<?php

/**
 * Settings OSSMailScanner logs view class
 * @package YetiForce.View
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 */
class Settings_OSSMailScanner_logs_View extends Settings_Vtiger_Index_View
{

	public function process(\App\Request $request)
	{
		$moduleName = $request->getModule();
		$ossMailScannerRecordModel = Vtiger_Record_Model::getCleanInstance('OSSMailScanner');

		$cronHistoryActionList = $ossMailScannerRecordModel->getScanHistory();
		$viewer = $this->getViewer($request);
		$viewer->assign('RecordModel', $ossMailScannerRecordModel);
		$viewer->assign('MODULENAME', $moduleName);
		$viewer->assign('WIDGET_CFG', $ossMailScannerRecordModel->getConfig(''));
		$viewer->assign('HISTORYACTIONLIST', $cronHistoryActionList);
		$viewer->assign('HISTORYACTIONLIST_NUM', $this->getNumLog());

		$stopButtonStatus = $ossMailScannerRecordModel->checkLogStatus();
		if (false !== $stopButtonStatus) {
			$viewer->assign('STOP_BUTTON_STATUS', 'true');
		} else {
			$viewer->assign('STOP_BUTTON_STATUS', 'false');
		}

		echo $viewer->view('logs.tpl', $request->getModule(false), true);
	}

	public function getNumLog()
	{
		$db = PearDatabase::getInstance();
		$limit = 30;
		$result = $db->query("SELECT COUNT(id) as num FROM vtiger_ossmails_logs");
		$numRecord = $db->query_result($result, 0, 'num');
		return ceil($numRecord / $limit);
	}
}

?>
