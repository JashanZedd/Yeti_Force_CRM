<?php

/**
 * ProjectTask ProjectTaskHandler handler class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 */
class ProjectTask_ProjectTaskHandler_Handler
{
	/**
	 * EntityAfterSave handler function.
	 *
	 * @param \App\EventHandler $eventHandler
	 */
	public function entityAfterSave(\App\EventHandler $eventHandler)
	{
		\App\DebugerEx::log('entityAfterSave');
		$recordModel = $eventHandler->getRecordModel();
		if ($recordModel->isNew()) {
			Vtiger_Module_Model::getInstance('ProjectMilestone')->updateProgressMilestone($recordModel->get('projectmilestoneid'));
			Vtiger_Module_Model::getInstance('Project')->updateProgress($recordModel->get('projectid'));
		} else {
			$delta = $recordModel->getPreviousValue();
			$calculateMilestone = [];
			$calculateProject = [];
			foreach ($delta as $name => $value) {
				if ($name === 'projectmilestoneid' || $name === 'estimated_work_time' || $name === 'projecttaskprogress') {
					if ($name === 'projectmilestoneid') {
						$calculateMilestone[$recordModel->get($name)] = true;
						$calculateMilestone[$value] = true;
					} else {
						$calculateMilestone[$recordModel->get('projectmilestoneid')] = true;
					}
					$calculateProject[$recordModel->get('projectid')] = true;
				} elseif ($name === 'projectid') {
					$calculateProject[$recordModel->get($name)] = true;
					$calculateProject[$value] = true;
				}
			}
			$milestoneModel = Vtiger_Module_Model::getInstance('ProjectMilestone');
			foreach ($calculateMilestone as $milestoneId => $val) {
				$milestoneModel->updateProgressMilestone($milestoneId);
			}
			$projectModel = Vtiger_Module_Model::getInstance('Project');
			foreach ($calculateProject as $projectId => $val) {
				$projectModel->updateProgress($projectId);
			}
		}
	}

	/**
	 * EntityAfterDelete handler function.
	 *
	 * @param \App\EventHandler $eventHandler
	 */
	public function entityAfterDelete(\App\EventHandler $eventHandler)
	{
		\App\DebugerEx::log('entityAfterDelete');
		Vtiger_Module_Model::getInstance('ProjectMilestone')->updateProgressMilestone($eventHandler->getRecordModel()->get('projectmilestoneid'));
		Vtiger_Module_Model::getInstance('Project')->updateProgress($eventHandler->getRecordModel()->get('projectid'));
	}

	/**
	 * EntityChangeState handler function.
	 *
	 * @param \App\EventHandler $eventHandler
	 */
	public function entityChangeState(\App\EventHandler $eventHandler)
	{
		\App\DebugerEx::log('entityChangeState');
		Vtiger_Module_Model::getInstance('ProjectMilestone')->updateProgressMilestone($eventHandler->getRecordModel()->get('projectmilestoneid'));
		Vtiger_Module_Model::getInstance('Project')->updateProgress($eventHandler->getRecordModel()->get('projectid'));
	}
}
