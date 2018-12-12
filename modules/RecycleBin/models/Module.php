<?php

/**
 * RecycleBin module model Class.
 *
 * @package   Model
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Dudek <a.dudek@yetiforce.com>
 */
class RecycleBin_Module_Model extends Vtiger_Module_Model
{
	public function getAllModuleList()
	{
		return \vtlib\Functions::getAllModules(1, true);
	}

	/**
	 * Funxtion to identify if the module supports quick search or not.
	 */
	public function isQuickSearchEnabled()
	{
		return false;
	}

	/**
	 * Delete all records from recycle to given date and module.
	 *
	 * @param string      $untilModifiedTime
	 * @param int         $userId
	 * @param string|bool $moduleName
	 *
	 * @throws \App\Exceptions\NoPermitted
	 */
	public static function deleteAllRecords(string $untilModifiedTime, int $userId)
	{
		$actualUserId = App\User::getCurrentUserId();
		try {
			App\User::setCurrentUserId($userId);
			$modulesList = \vtlib\Functions::getAllModules(true, false, 0);
			foreach ($modulesList as $module) {
				$recordIds = (new \App\Db\Query())->select(['crmid'])->from('vtiger_crmentity')->where(
					['and',
						['deleted' => 1],
						['setype' => $module['name']],
						['<=', 'modifiedtime', $untilModifiedTime]])->column();
				if ($recordIds) {
					foreach ($recordIds as $recordId) {
						if (App\Record::isCrmExist($recordId)) {
							$recordModel = Vtiger_Record_Model::getInstanceById($recordId);
							if (!$recordModel->privilegeToDelete()) {
								continue;
							}
							$recordModel->delete();
							unset($recordModel);
						}
					}
				}
			}
			App\User::setCurrentUserId($actualUserId);
		} catch (\Throwable $ex) {
			\App\Log::error($ex->getMessage());
			App\User::setCurrentUserId($actualUserId);
		}
	}

	/**
	 * Delete records given by ids in array.
	 *
	 * @param int[] $recordIds
	 *
	 * @throws Exception
	 */
	//remove?
	public static function deleteRecords($recordIds)
	{
		if ($recordIds) {
			$userPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
			foreach ($recordIds as $recordId) {
				$recordModel = Vtiger_Record_Model::getInstanceById($recordId);
				if (!$userPriviligesModel->hasModuleActionPermission($recordModel->getModuleName(), 'MassDelete')) {
					throw new \App\Exceptions\NoPermitted('LBL_PERMISSION_DENIED', 406);
				}
				if (!$recordModel->privilegeToDelete()) {
					continue;
				}
				$recordModel->delete();
				unset($recordModel);
			}
		}
	}
}
