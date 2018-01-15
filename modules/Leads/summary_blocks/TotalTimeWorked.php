<?php

/**
 * TotalTimeWorked class
 * @package YetiForce.SummaryBlock
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 */
class TotalTimeWorked
{

	public $name = 'Total time worked';
	public $sequence = 5;
	public $reference = 'OSSTimeControl';

	/**
	 * Process
	 * @param Vtiger_Record_Model $recordModel
	 * @return int
	 */
	public function process(Vtiger_Record_Model $recordModel)
	{

		\App\Log::trace('Entering TotalTimeWorked::process() method ...');
		$timecontrol = (new App\Db\Query())->from('vtiger_osstimecontrol')
			->innerJoin('vtiger_crmentity', 'vtiger_crmentity.crmid = vtiger_osstimecontrol.osstimecontrolid')
			->where(['vtiger_crmentity.deleted' => 0, 'vtiger_osstimecontrol.link' => $recordModel->getId()])
			->sum('vtiger_osstimecontrol.sum_time');
		$decimalTimeFormat = vtlib\Functions::decimalTimeFormat($timecontrol);
		\App\Log::trace('Exiting TotalTimeWorked::process() method ...');
		return $decimalTimeFormat['short'];
	}
}
