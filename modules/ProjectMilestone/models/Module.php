<?php
/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class ProjectMilestone_Module_Model extends Vtiger_Module_Model
{

	/**
	 * Add condition to sql query
	 * @param <Vtiger_ListView_Model> $listviewModel
	 * @param string $listQuery
	 * @return string
	 */
	public function getQueryByRelatedField(Vtiger_ListView_Model $listviewModel, $listQuery)
	{
		if ($listviewModel->get('src_module') === 'Project' && !$listviewModel->isEmpty('filterFields')) {
			$filterFields = $listviewModel->get('filterFields');
			if (!empty($filterFields['projectid'])) {
				return $listQuery . ' AND projectid = ' . $filterFields['projectid'];
			}
		}
	}

	public function updateProgressMilestone($id)
	{
		$adb = PearDatabase::getInstance();

		if (!isRecordExists($id)) {
			return;
		}
		$focus = CRMEntity::getInstance($this->getName());
		$relatedListMileston = $focus->get_dependents_list($id, $this->getId(), \App\Module::getModuleId('ProjectTask'));
		$resultMileston = $adb->query($relatedListMileston['query']);
		$num = $adb->num_rows($resultMileston);
		$estimatedWorkTime = 0;
		$progressInHours = 0;
		for ($i = 0; $i < $num; $i++) {
			$row = $adb->query_result_rowdata($resultMileston, $i);
			$estimatedWorkTime += $row['estimated_work_time'];
			$recordProgress = ($row['estimated_work_time'] * (int) $row['projecttaskprogress']) / 100;
			$progressInHours += $recordProgress;
		}
		if (!$estimatedWorkTime) {
			return;
		}
		$projectMilestoneProgress = round((100 * $progressInHours) / $estimatedWorkTime);
		$focus->retrieve_entity_info($id, $this->getName());
		$focus->column_fields['projectmilestone_progress'] = $projectMilestoneProgress . '%';
		$focus->column_fields['mode'] = 'edit';
		$focus->saveentity($this->getName(), $id);
	}
}
