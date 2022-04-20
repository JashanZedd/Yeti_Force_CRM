<?php

/**
 * Calendar actions.
 *
 * @package Action
 *
 * @copyright 	YetiForce S.A.
 * @license 	YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author   	Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
/**
 * Vtiger_Calendar_Action class.
 */
class Vtiger_Calendar_Action extends \App\Controller\Action
{
	use \App\Controller\ExposeMethod;

	/**
	 * Function to check permission.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\NoPermitted
	 */
	public function checkPermission(App\Request $request)
	{
		$moduleName = $request->getModule();
		$userPrivilegesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$userPrivilegesModel->hasModulePermission($moduleName) || !\method_exists(Vtiger_Module_Model::getInstance($moduleName), 'getCalendarViewUrl')) {
			throw new \App\Exceptions\NoPermitted('LBL_PERMISSION_DENIED', 406);
		}
		if ('updateEvent' === $request->getMode() && ($request->isEmpty('id', true) || !\App\Privilege::isPermitted($moduleName, 'EditView', $request->getInteger('id')))) {
			throw new \App\Exceptions\NoPermittedToRecord('ERR_NO_PERMISSIONS_FOR_THE_RECORD', 406);
		}
	}

	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('getEvents');
		$this->exposeMethod('getEventsYear');
		$this->exposeMethod('getCountEvents');
		$this->exposeMethod('updateEvent');
		$this->exposeMethod('getCountEventsGroup');
	}

	public function getEvents(App\Request $request)
	{
		$record = $this->getCalendarModel($request);
		$record->remove('types');
		$entity = array_merge($record->getEntity(), $record->getPublicHolidays());
		$response = new Vtiger_Response();
		$response->setResult($entity);
		$response->emit();
	}

	/**
	 * Get events for year view.
	 *
	 * @param \App\Request $request
	 */
	public function getEventsYear(App\Request $request)
	{
		$record = $this->getCalendarModel($request);
		$entity = array_merge($record->getEntityYearCount(), $record->getPublicHolidays());
		$response = new Vtiger_Response();
		$response->setResult($entity);
		$response->emit();
	}

	/**
	 * Get count Events for extended calendar's left column.
	 *
	 * @param \App\Request $request
	 */
	public function getCountEvents(App\Request $request)
	{
		$record = $this->getCalendarModel($request);
		$entity = $record->getEntityRecordsCount();
		$response = new Vtiger_Response();
		$response->setResult($entity);
		$response->emit();
	}

	/**
	 * Get count Events for extended calendar's left column.
	 *
	 * @param \App\Request $request
	 */
	public function getCountEventsGroup(App\Request $request)
	{
		$request->delete('end');
		$record = $this->getCalendarModel($request);
		$result = [];
		foreach ($request->getArray('dates', 'Date') as $datePair) {
			$record->set('start', $datePair[0] . ' 00:00:00');
			$record->set('end', $datePair[1] . ' 23:59:59');
			$result[] = $record->getEntityRecordsCount();
		}
		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}

	/**
	 * Get calendar model.
	 *
	 * @param App\Request $request
	 *
	 * @return Vtiger_Calendar_Model
	 */
	public function getCalendarModel(App\Request $request): Vtiger_Calendar_Model
	{
		$record = Vtiger_Calendar_Model::getInstance($request->getModule());
		$record->set('user', $request->getArray('user', 'Alnum'));
		$record->set('types', $request->getArray('types', 'Text'));
		$record->set('time', $request->isEmpty('time') ? '' : $request->getByType('time'));
		if ($request->has('start') && $request->has('end')) {
			$record->set('start', $request->getByType('start', 'DateInUserFormat'));
			$record->set('end', $request->getByType('end', 'DateInUserFormat'));
		}
		if ($request->has('filters')) {
			$record->set('filters', $request->getByType('filters', 'Alnum'));
		}
		if ($request->has('cvid')) {
			$record->set('customFilter', $request->getInteger('cvid'));
		}
		return $record;
	}

	/**
	 * Update event.
	 *
	 * @param App\Request $request
	 */
	public function updateEvent(App\Request $request)
	{
		$record = Vtiger_Calendar_Model::getInstance($request->getModule());
		$success = $record->updateEvent($request->getInteger('id'), $request->getByType('start', 'DateTimeInUserFormat'), $request->getByType('end', 'DateTimeInUserFormat'));
		$response = new Vtiger_Response();
		$response->setResult($success);
		$response->emit();
	}
}
