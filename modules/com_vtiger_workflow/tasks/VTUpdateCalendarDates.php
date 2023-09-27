<?php

/**
 * Update the dates of created events automatically.
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class VTUpdateCalendarDates extends VTTask
{
	/**
	 * Execute immediately.
	 *
	 * @var bool
	 */
	public $executeImmediately = true;

	/**
	 * Get field names.
	 *
	 * @return array
	 */
	public function getFieldNames()
	{
		return [];
	}

	/**
	 * Execute task.
	 *
	 * @param Vtiger_Record_Model $recordModel
	 */
	public function doTask($recordModel)
	{
		$entityId = $recordModel->getId();

		$delta = $recordModel->getPreviousValue();
		if (!$delta) {
			return;
		}
		$query = (new \App\Db\Query())->from('vtiger_activity_update_dates')->innerJoin('com_vtiger_workflowtasks', 'com_vtiger_workflowtasks.task_id = vtiger_activity_update_dates.task_id')->where(['vtiger_activity_update_dates.parent' => $entityId]);
		$dataReader = $query->createCommand()->query();
		while ($row = $dataReader->read()) {
			$task = new \ArrayObject(unserialize($row['task']));
			$rowRecordModel = Vtiger_Record_Model::getInstanceById($row['activityid'], 'Calendar');

			if ('wfRunTime' === $task['datefield_start']) {
				$baseDateStart = date('Y-m-d H:i:s');
			} else {
				$baseDateStart = $recordModel->get($task['datefield_start']);
				if ('' === $baseDateStart) {
					$baseDateStart = date('Y-m-d');
				}
			}
			preg_match('/\d\d\d\d-\d\d-\d\d/', $baseDateStart, $match);
			$baseDateStart = new DateTime($match[0]);
			$daysStart = $task['days_start'] + ('before' === strtolower($task['direction_start']) ? -1 : 1);
			$dateStart = $baseDateStart->modify("+ $daysStart days")->format('Y-m-d');

			if ('wfRunTime' === $task['datefield_end']) {
				$baseDateEnd = date('Y-m-d H:i:s');
			} else {
				$baseDateEnd = $recordModel->get($task['datefield_end']);
				if ('' === $baseDateEnd) {
					$baseDateEnd = date('Y-m-d');
				}
			}
			preg_match('/\d\d\d\d-\d\d-\d\d/', $baseDateEnd, $match);
			$baseDateEnd = new DateTime($match[0]);
			$daysEnd = $task['days_end'] + ('before' === strtolower($task['direction_start']) ? -1 : 1);
			$duedate = $baseDateEnd->modify("+ $daysEnd days")->format('Y-m-d');

			$rowRecordModel->set('date_start', $dateStart);
			$rowRecordModel->set('due_date', $duedate);
			$rowRecordModel->save();
		}
	}
}
