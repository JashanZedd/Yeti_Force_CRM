<?php

/**
 * TaskCompleted class
 * @package YetiForce.SummaryBlock
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 */
class TaskCompleted
{

	public $name = 'LBL_TASKS_COMPLETED';
	public $sequence = 1;
	public $reference = 'ProjectTask';

	/**
	 * Process
	 * @param Vtiger_Record_Model $instance
	 * @return int
	 */
	public function process(Vtiger_Record_Model $instance)
	{

		\App\Log::trace('Entering TaskCompleted::process() method ...');
		$count = (new App\Db\Query())->from('vtiger_projecttask')->innerJoin('vtiger_crmentity', 'vtiger_projecttask.projecttaskid = vtiger_crmentity.crmid')->where(['vtiger_projecttask.projectid' => $instance->getId(), 'vtiger_crmentity.deleted' => 0, 'vtiger_projecttask.projecttaskstatus' => 'Completed'])->count();
		\App\Log::trace('Exiting TaskCompleted::process() method ...');
		return $count;
	}
}
