<?php

/**
 * List view model class for fields dependency settings.
 *
 * @package   Settings.Model
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Sołek <a.solek@yetiforce.com>
 */

/**
 * Settings fields dependency list view model class.
 */
class Settings_FieldsDependency_ListView_Model extends Settings_Vtiger_ListView_Model
{
	/**
	 * Function to get the list view entries.
	 *
	 * @param Vtiger_Paging_Model $pagingModel
	 *
	 * @return array
	 */
	public function getListViewEntries($pagingModel)
	{
		$module = $this->getModule();
		$parentModuleName = $module->getParentName();
		if (!empty($parentModuleName)) {
			$qualifiedModuleName = $parentModuleName . ':' . $module->getName();
		}
		$recordModelClass = Vtiger_Loader::getComponentClassName('Model', 'Record', $qualifiedModuleName);
		$listFields = array_keys($module->listFields);
		$listFields[] = $module->baseIndex;

		$query = (new \App\Db\Query())->select($listFields)
			->from($module->baseTable);

		$sourceModule = $this->get('sourceModule');
		if (!empty($sourceModule)) {
			$query->where(['tabid' => $sourceModule]);
		}
		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		$query->limit($pageLimit + 1)->offset($startIndex);
		$orderBy = $this->getForSql('orderby');
		if (!empty($orderBy)) {
			$query->orderBy($orderBy . ' ' . $this->getForSql('sortorder'));
		}

		$dataReader = $query->createCommand()->query();
		$listViewRecordModels = [];

		while ($row = $dataReader->read()) {
			$record = new $recordModelClass();
			$record->setData($row);
			$listViewRecordModels[$record->getId()] = $record;
		}
		$pagingModel->calculatePageRange($dataReader->count());
		if ($dataReader->count() > $pageLimit) {
			array_pop($listViewRecordModels);
			$pagingModel->set('nextPageExists', true);
		} else {
			$pagingModel->set('nextPageExists', false);
		}
		$dataReader->close();

		return $listViewRecordModels;
	}
}
