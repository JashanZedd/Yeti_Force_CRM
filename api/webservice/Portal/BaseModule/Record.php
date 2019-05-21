<?php

namespace Api\Portal\BaseModule;

/**
 * Get record detail class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 * @author    Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
class Record extends \Api\Core\BaseAction
{
	/** @var string[] Allowed request methods */
	public $allowedMethod = ['GET', 'DELETE', 'PUT', 'POST'];

	/**
	 * Record model.
	 *
	 * @var \Vtiger_Record_Model
	 */
	protected $recordModel = false;

	/**
	 * Check permission to method.
	 *
	 * @throws \Api\Core\Exception
	 *
	 * @return bool
	 */
	public function checkPermission()
	{
		parent::checkPermission();
		$moduleName = $this->controller->request->getModule();
		$method = $this->controller->method;
		if ('POST' === $method) {
			$this->recordModel = \Vtiger_Record_Model::getCleanInstance($moduleName);
			if (!$this->recordModel->isCreateable()) {
				throw new \Api\Core\Exception('No permissions to create record', 401);
			}
		} else {
			$record = $this->controller->request->get('record');
			if (!$record || !\App\Record::isExists($record, $moduleName)) {
				throw new \Api\Core\Exception('Record doesn\'t exist', 401);
			}
			$this->recordModel = \Vtiger_Record_Model::getInstanceById($record, $moduleName);
			switch ($method) {
				case 'DELETE':
					if (!$this->recordModel->privilegeToDelete()) {
						throw new \Api\Core\Exception('No permissions to remove record', 401);
					}
					break;
				case 'GET':
					if (!$this->recordModel->isViewable()) {
						throw new \Api\Core\Exception('No permissions to view record', 401);
					}
					break;
				case 'PUT':
					if (!$this->recordModel->isEditable()) {
						throw new \Api\Core\Exception('No permissions to edit record', 401);
					}
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Get record detail.
	 *
	 * @return array
	 */
	public function get()
	{
		$moduleName = $this->controller->request->get('module');
		$record = $this->controller->request->get('record');
		$model = $this->recordModel;
		$rawData = $model->getData();
		$moduleModel = $model->getModule();

		$displayData = $fieldsLabel = [];
		$moduleBlockFields = \Vtiger_Field_Model::getAllForModule($moduleModel);
		foreach ($moduleBlockFields as $moduleFields) {
			foreach ($moduleFields as $moduleField) {
				$block = $moduleField->get('block');
				if (empty($block)) {
					continue;
				}
				$fieldLabel = \App\Language::translate($moduleField->get('label'), $moduleName);
				$displayData[$moduleField->getName()] = $model->getDisplayValue($moduleField->getName(), $record, true);
				$fieldsLabel[$moduleField->getName()] = $fieldLabel;
				if ($moduleField->isReferenceField()) {
					$refereneModule = $moduleField->getUITypeModel()->getReferenceModule($model->get($moduleField->getName()));
					$rawData[$moduleField->getName() . '_module'] = $refereneModule ? $refereneModule->getName() : null;
				}
				if ($moduleField->getFieldDataType() === 'taxes') {
					$rawData[$moduleField->getName() . '_info'] = \Vtiger_Taxes_UIType::getValues($rawData[$moduleField->getName()]);
				}
			}
		}
		$inventory = false;
		$summaryInventory = [];
		if ($model->getModule()->isInventory()) {
			$rawInventory = $model->getInventoryData();
			$inventory = [];
			$inventoryModel = \Vtiger_Inventory_Model::getInstance($moduleName);
			$inventoryFields = $inventoryModel->getFields();
			foreach ($rawInventory as $row) {
				$inventoryRow = [];
				foreach ($inventoryFields as $name => $field) {
					$inventoryRow[$name] = $field->getDisplayValue($row[$name], $row, true);
				}
				$inventory[] = $inventoryRow;
			}
			foreach ($inventoryFields as $name => $field) {
				if ($field->isSummary()) {
					$summaryInventory[$name] = \CurrencyField::convertToUserFormat($field->getSummaryValuesFromData($rawInventory), null, true);
				}
			}
		}
		$response = [
			'name' => $model->getName(),
			'id' => $model->getId(),
			'fields' => $fieldsLabel,
			'data' => $displayData,
			'inventory' => $inventory,
			'summary_inventory' => $summaryInventory
		];
		if (1 === (int) $this->controller->headers['x-raw-data']) {
			$response['rawData'] = $rawData;
			$response['rawInventory'] = $rawInventory;
		}
		return $response;
	}

	/**
	 * Delete record.
	 *
	 * @return bool
	 */
	public function delete()
	{
		$this->recordModel->delete();

		return true;
	}

	/**
	 * Edit record.
	 *
	 * @return array
	 */
	public function put()
	{
		return $this->post();
	}

	/**
	 * Create record.
	 *
	 * @return array
	 */
	public function post()
	{
		$model = (new \Api\Portal\Save($this->controller->app['id']))->saveRecord($this->controller->request);
		return ['id' => $model->getId()];
	}
}
