<?php

namespace App;

/**
 * RecordConverter class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Adrian Koń <a.kon@yetiforce.com>
 */
class RecordConverter extends Base
{
	/**
	 * Source module name.
	 *
	 * @var string
	 */
	public $sourceModule = '';

	/**
	 * Source module model.
	 *
	 * @var \Vtiger_Module_Model
	 */
	public $sourceModuleModel;

	/**
	 * Destiny module name.
	 *
	 * @var string
	 */
	public $destinyModule = '';

	/**
	 * Destiny module model.
	 *
	 * @var \Vtiger_Module_Model
	 */
	public $destinyModuleModel;

	/**
	 * Record modesl of created records.
	 *
	 * @var Vtiger_Record_Model[]
	 */
	public $cleanRecordModels = [];

	/**
	 * Source record models.
	 *
	 * @var Vtiger_Record_Model[]
	 */
	public $recordModels = [];

	/**
	 * Convert field mapping.
	 *
	 * @var array|string
	 */
	public $fieldMapping;

	/**
	 * Convert inventory mapping.
	 *
	 * @var array|string
	 */
	public $inventoryMapping;

	/**
	 * Contains values from text parser.
	 *
	 * @var array
	 */
	public $textParserValues = [];

	/**
	 * Source module inventory fields.
	 *
	 * @var array
	 */
	public $sourceInvFields = [];

	/**
	 * Destiny module inventory fields.
	 *
	 * @var array
	 */
	public $destinyInvFields = [];

	/**
	 * Variable determines the possibility of fields mappigng.
	 *
	 * @var bool
	 */
	public $fieldMappingExecute = false;

	/**
	 * Variable determines the possibility of inventory fields mapping.
	 *
	 * @var bool
	 */
	public $inventoryMappingExecute = false;

	/**
	 * Number of created recors.
	 *
	 * @var int
	 */
	public $createdRecords = 0;

	/**
	 * @var string
	 */
	public $error = '';

	/**
	 * Variable determines the type of group records.
	 *
	 * @var bool
	 */
	public $groupRecordConvert = false;

	/**
	 * Variable determines if merge field exist.
	 *
	 * @var bool
	 */
	public $isFieldMergeExists = false;

	/**
	 * Variable determines is redirect to edit view.
	 *
	 * @var bool
	 */
	public $idEdit = false;

	/**
	 * Function to get the instance of the record converter model.
	 *
	 * @return \self
	 */
	public static function getInstanceById($id, $moduleName = '')
	{
		$query = (new \App\Db\Query())->from('a_#__record_converter')->where(['id' => $id]);
		if ($moduleName) {
			$query->andWhere(['source_module' => \App\Module::getModuleId($moduleName)]);
		}
		$row = $query->one(\App\Db::getInstance('admin'));
		$self = new self();
		if ($row) {
			$self->setData($row);
		} else {
			\App\Log::error("Could not find record converter id: $id module name: $moduleName");
			throw new \App\Exceptions\AppException('LBL_NOT_FOUND_RECORD_CONVERTER');
		}
		return $self;
	}

	/**
	 * Function check if convert for module and view exist.
	 *
	 * @param int    $moduleName
	 * @param string $view
	 *
	 * @return bool
	 */
	public static function isActive($moduleName, $view = '')
	{
		return self::getQuery($moduleName, $view)->exists(\App\Db::getInstance('admin'));
	}

	/**
	 * Function gets module converters.
	 *
	 * @param string $moduleName
	 * @param string $view
	 *
	 * @return array
	 */
	public static function getModuleConverters($moduleName, $view = '')
	{
		return self::getQuery($moduleName, $view)->createCommand(\App\Db::getInstance('admin'))->queryAllByGroup(1);
	}

	/**
	 * Function return query about module converters in view.
	 *
	 * @param string|int $moduleName
	 * @param string     $view
	 *
	 * @return \App\Db\Query
	 */
	public static function getQuery($moduleName, $view = '')
	{
		if (is_string($moduleName)) {
			$moduleName = \App\Module::getModuleId($moduleName);
		}
		if (Cache::has('getQueryRecordConverter', $moduleName . $view)) {
			return Cache::get('getQueryRecordConverter', $moduleName . $view);
		}
		$query = (new \App\Db\Query())->from('a_#__record_converter')->where(['source_module' => $moduleName, 'status' => 1]);
		if ($view) {
			$query->andWhere(['or like', 'view',
				[
					$view,
					'%,' . $view . ',%',
					'%' . $view . ',',
					$view . ',%',
					'%,' . $view,
				], false,
			]);
		}
		Cache::save('getQueryRecordConverter', $moduleName . $view, $query);
		return $query;
	}

	/**
	 * Function get number of created records.
	 *
	 * @param array $records
	 *
	 * @return int
	 */
	public function countCreatedRecords($records)
	{
		$modulesAmount = 0;
		foreach (explode(',', $this->get('destiny_module')) as $destinyModuleId) {
			$destinyModuleName = \App\Module::getModuleName($destinyModuleId);
			if (\App\Privilege::isPermitted($destinyModuleName, 'CreateView')) {
				$modulesAmount++;
			}
		}
		if ($this->get('field_merge')) {
			return count($this->getGroupRecords($records)) * $modulesAmount;
		} else {
			return count($records) * $modulesAmount;
		}
	}

	/**
	 * Function variable initializing.
	 *
	 * @throws \App\Exceptions\AppException
	 */
	public function init()
	{
		$this->sourceModule = \App\Module::getModuleName($this->get('source_module'));
		$this->sourceModuleModel = \Vtiger_Module_Model::getInstance($this->sourceModule);
		$this->fieldMapping = $this->get('field_mappging') ? \App\Json::decode($this->get('field_mappging')) : [];
		$this->inventoryMapping = $this->get('inv_field_mapping') ? \App\Json::decode($this->get('inv_field_mapping')) : [];
		$inventoryField = \Vtiger_InventoryField_Model::getInstance($this->sourceModule);
		$this->sourceInvFields = $inventoryField->getFields();
		$this->defaultValuesCreatedRecord = $this->get('default_values') ? \App\Json::decode($this->get('default_values')) : [];
	}

	/**
	 * Main function of class.
	 *
	 * @param array $records
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return array
	 */
	public function process($records)
	{
		$this->init();
		$createdRecordIds = [];
		if ($this->get('destiny_module')) {
			$recordsAmount = count($records);
			foreach (explode(',', $this->get('destiny_module')) as $destinyModuleId) {
				$destinyModuleName = \App\Module::getModuleName($destinyModuleId);
				if (!\App\Privilege::isPermitted($destinyModuleName, 'CreateView')) {
					\App\Log::warning("No permitted to action CreateView in module $destinyModuleName in view RecordConventer");
					continue;
				}
				$this->initDestinyModuleValues($destinyModuleName);
				if ($this->fieldMapping && $this->fieldMapping['mapping'][$this->destinyModuleModel->getId()] && (!$this->isFieldMergeExists || $recordsAmount === 1)) {
					$this->fieldMappingExecute = true;
				}
				if ($this->inventoryMapping && $this->sourceModuleModel->isInventory() && $this->destinyModuleModel->isInventory()) {
					$this->inventoryMappingExecute = true;
				}
				if ($this->isFieldMergeExists) {
					$this->groupRecordConvert = true;
					$createdRecordIds = $this->getRecordsGroupBy($records);
				} else {
					$createdRecordIds = $this->getRecordModelsWithoutMerge($records);
				}
			}
		}
		return $createdRecordIds;
	}

	/**
	 * Function initializing.
	 *
	 * @param string $moduleName
	 */
	public function initDestinyModuleValues(string $moduleName)
	{
		$this->destinyModule = $moduleName;
		$this->destinyModuleModel = \Vtiger_Module_Model::getInstance($this->destinyModule);
		$inventoryField = \Vtiger_InventoryField_Model::getInstance($this->destinyModule);
		$this->destinyInvFields = $inventoryField->getFields();
		$this->isFieldMergeExists = $this->checkFieldMerge();
	}

	/**
	 * Function to edit process.
	 *
	 * @param int    $record
	 * @param string $destinyModule
	 *
	 * @throws \App\Exceptions\AppException
	 *
	 * @return Vtiger_Module_Model
	 */
	public function processToEdit($record, $destinyModule)
	{
		$this->initDestinyModuleValues($destinyModule);
		$this->init();
		$this->isFieldMergeExists = $this->checkFieldMerge();
		if ($this->fieldMapping && $this->fieldMapping['mapping'][$this->destinyModuleModel->getId()] && $this->isFieldMergeExists) {
			$this->fieldMappingExecute = true;
		}
		if ($this->inventoryMapping && $this->sourceModuleModel->isInventory() && $this->destinyModuleModel->isInventory()) {
			$this->inventoryMappingExecute = true;
		}
		$this->getRecordModelsWithoutMerge([$record]);
		return \current($this->cleanRecordModels);
	}

	/**
	 * Function get query to group records.
	 *
	 * @param array $records
	 *
	 * @return array
	 */
	public function getGroupRecords($records)
	{
		$fieldModel = \Vtiger_Field_Model::getInstance($this->get('field_merge'), $this->sourceModuleModel);
		$focus = \CRMEntity::getInstance($this->sourceModule);
		$result = (new \App\Db\Query())->select([$fieldModel->getTableName() . ".{$fieldModel->getColumnName()}", $focus->tab_name_index[$fieldModel->getTableName()]])
			->from($fieldModel->getTableName())->where([$focus->tab_name_index[$fieldModel->getTableName()] => $records])->createCommand()->queryAllByGroup(2);
		return $result;
	}

	/**
	 * Function prepare records model group by field merge.
	 *
	 * @param array $records
	 *
	 * @return array
	 */
	public function getRecordsGroupBy($records)
	{
		$createdRecordIds = [];
		$groupRecords = $this->getGroupRecords($records);
		foreach ($groupRecords as $groupBy => $recordsId) {
			$this->cleanRecordModels[$groupBy] = \Vtiger_Record_Model::getCleanInstance($this->destinyModule);
			$this->cleanRecordModels[$groupBy]->set($this->fieldMapping['field_merge'][$this->destinyModuleModel->getId()], $groupBy);
			foreach ($recordsId as $recordId) {
				if (!isset($this->recordModels[$groupBy][$recordId])) {
					$this->recordModels[$groupBy][$recordId] = \Vtiger_Record_Model::getInstanceById($recordId, $this->sourceModule);
				}
			}
			if ($this->inventoryMappingExecute) {
				$this->processInventoryMapping();
			}
			if ($this->get('check_duplicate')) {
				$this->checkDuplicate();
			}
			$createdRecordIds[] = $this->saveChanges();
		}
		return $createdRecordIds;
	}

	/**
	 * Function prepare records model.
	 *
	 * @param array $records
	 *
	 * @return array
	 */
	public function getRecordModelsWithoutMerge($records)
	{
		$createdRecordIds = [];
		foreach ($records as $recordId) {
			$this->cleanRecordModels[$recordId] = \Vtiger_Record_Model::getCleanInstance($this->destinyModule);
			if (!isset($this->recordModels[$recordId])) {
				$this->recordModels[$recordId] = \Vtiger_Record_Model::getInstanceById($recordId, $this->sourceModule);
			}
			if ($this->fieldMappingExecute) {
				$this->processFieldMapping();
			}
			if ($this->inventoryMappingExecute) {
				$this->processInventoryMapping();
			}
			if ($this->get('check_duplicate')) {
				$this->checkDuplicate();
			}
			if (!$this->isEdit) {
				$createdRecordIds[] = $this->saveChanges();
			}
		}
		return $createdRecordIds;
	}

	/**
	 * Function prepare mapping fields.
	 */
	public function processFieldMapping()
	{
		$this->checkFieldMappingFields();
		if (isset($this->fieldMapping['mapping'][$this->destinyModuleModel->getId()])) {
			foreach ($this->cleanRecordModels as $key => $cleanRecordModel) {
				$referenceRecordModel = &$this->cleanRecordModels[$key];
				$textParser = \App\TextParser::getInstanceByModel($this->recordModels[$key]);
				foreach ($this->fieldMapping['mapping'][$this->destinyModuleModel->getId()] as $destinyField => $sourceField) {
					if (!isset($this->textParserValues[$key][$sourceField])) {
						$textParser->setContent($sourceField);
						$this->textParserValues[$key][$sourceField] = $textParser->parse()->getContent();
					}
					$referenceRecordModel->set($destinyField, $this->textParserValues[$key][$sourceField]);
				}
			}
		}
	}

	/**
	 * Function save changes in new record models.
	 *
	 * @return int
	 */
	public function saveChanges()
	{
		foreach ($this->cleanRecordModels as $recordModel) {
			try {
				$recordModel->save();
				$this->createdRecords++;
				unset($this->cleanRecordModels);
			} catch (\Error $ex) {
				$this->createdRecords--;
				$this->error = $ex->getMessage();
			}
		}
		return $recordModel->getId();
	}

	/**
	 * Function prepare inventory mapping.
	 */
	public function processInventoryMapping()
	{
		if ($this->inventoryMapping) {
			$invData = [];
			$counter = 1;
			if ($this->inventoryMapping[0] === 'auto') {
				$inventoryFields = array_merge($this->sourceInvFields, $this->destinyInvFields);
				foreach ($this->cleanRecordModels as $groupBy => $newRecordModel) {
					if (!is_array($this->recordModels[$groupBy])) {
						$sourceRecordModels = [$this->recordModels[$groupBy]];
					} else {
						$sourceRecordModels = $this->recordModels[$groupBy];
					}
					foreach ($sourceRecordModels as $recordModel) {
						if (!is_array($recordModel)) {
							$recordModel = [$recordModel];
						}
						foreach ($recordModel as $recordModelGroupBy) {
							foreach ($recordModelGroupBy->getInventoryData() as $inventoryRow) {
								foreach ($inventoryFields as $columnName => $fieldModel) {
									$invData[$groupBy][$columnName . $counter] = $inventoryRow[$columnName];
									$fieldCustomColumn = $fieldModel->getCustomColumn();
									if ($fieldCustomColumn) {
										foreach (array_keys($fieldCustomColumn) as $customColumn) {
											$inventoryData[$groupBy][$counter][$customColumn] = $inventoryRow[$customColumn];
											$invData[$groupBy][$customColumn . $counter] = $inventoryRow[$customColumn];
										}
									}
								}
								$invData[$groupBy]['seq' . $counter] = $counter;
								$invData[$groupBy]['inventoryItemsNo'] = $counter;
								$counter++;
							}
						}
					}
				}
				$newRecordModel->setInventoryRawData(new \App\Request($invData[$groupBy], false));
				$newRecordModel->initInventoryData();
			} else {
				foreach ($this->cleanRecordModels as $groupBy => $newRecordModel) {
					if (!is_array($this->recordModels[$groupBy])) {
						$sourceRecordModels = [$this->recordModels[$groupBy]];
					} else {
						$sourceRecordModels = $this->recordModels[$groupBy];
					}
					foreach ($sourceRecordModels as $recordModel) {
						if (!is_array($recordModel)) {
							$recordModel = [$recordModel];
						}
						foreach ($recordModel as $recordModelGroupBy) {
							foreach ($recordModelGroupBy->getInventoryData() as $inventoryRow) {
								if (isset($this->inventoryMapping[$this->destinyModuleModel->getId()])) {
									foreach ($this->inventoryMapping[$this->destinyModuleModel->getId()] as $destinyField => $sourceField) {
										$fieldCustomColumn = $this->destinyInvFields[$destinyField]->getCustomColumn();
										if ($fieldCustomColumn) {
											foreach (array_keys($fieldCustomColumn) as $customColumn) {
												$invData[$groupBy][$customColumn . $counter] = $inventoryRow[$customColumn];
											}
										}
										$invData[$groupBy][$destinyField . $counter] = $inventoryRow[$sourceField];
									}
									$invData[$groupBy]['name' . $counter] = $inventoryRow['id'];
									$invData[$groupBy]['seq' . $counter] = $counter;
									$invData[$groupBy]['inventoryItemsNo'] = $counter++;
								}
							}
						}
					}
				}
				$newRecordModel->setInventoryRawData(new \App\Request($invData[$groupBy], false));
				$newRecordModel->initInventoryData();
			}
		}
	}

	/**
	 * Function check mapped fields.
	 */
	public function checkFieldMappingFields()
	{
		if (isset($this->fieldMapping['mapping'][$this->destinyModuleModel->getId()])) {
			foreach ($this->fieldMapping['mapping'][$this->destinyModuleModel->getId()] as $destinyField => $sourceField) {
				if (!$this->destinyModuleModel->getField($destinyField)) {
					unset($this->fieldMapping['mapping'][$this->destinyModuleModel->getId()][$destinyField]);
				}
			}
		}
	}

	/**
	 * Function check if merge can be execute.
	 *
	 * @return bool
	 */
	public function checkFieldMerge()
	{
		if (isset($this->fieldMapping['field_merge'])) {
			$destinyReferenceFields = $this->destinyModuleModel->getFieldsByReference();
			$referenceDestinyField = $this->fieldMapping['field_merge'][$this->destinyModuleModel->getId()];
			if ($referenceDestinyField) {
				if (!$this->destinyModuleModel->getField($referenceDestinyField) || !$this->sourceModuleModel->getField($this->get('field_merge')) || !isset($destinyReferenceFields[$referenceDestinyField])) {
					return false;
				} else {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Function get query for searching duplicates.
	 *
	 * @return \App\Db\Query
	 */
	public function getQueryForDuplicate()
	{
		$focus = \CRMEntity::getInstance($this->destinyModule);
		return (new \App\Db\Query())->from($focus->table_name)->innerJoin($focus->customFieldTable[0], $focus->table_name . '.' . $focus->table_index . '=' . $focus->customFieldTable[0] . '.' . $focus->customFieldTable[1])
			->innerJoin('vtiger_crmentity', $focus->table_name . '.' . $focus->table_index . '= vtiger_crmentity.crmid');
	}

	/**
	 * Function check if exist duplicate of records.
	 */
	public function checkDuplicate()
	{
		if (isset($this->fieldMapping['field_merge'][$this->destinyModuleModel->getId()])) {
			$referenceDestinyField = $this->fieldMapping['field_merge'][$this->destinyModuleModel->getId()];
			if ($referenceDestinyField) {
				$query = $this->getQueryForDuplicate();
				foreach ($this->cleanRecordModels as $groupBy => $recordModel) {
					$columnsToCheck = [];
					if ($this->isFieldMergeExists) {
						if ($this->groupRecordConvert) {
							$columnsToCheck[$referenceDestinyField] = $groupBy;
						} else {
							$sourceRecordModel = \Vtiger_Record_Model::getInstanceById($groupBy, $this->sourceModule);
							$columnsToCheck[$referenceDestinyField] = $sourceRecordModel->get($this->get('field_merge'));
						}
					} else {
						foreach ($this->fieldMapping['mapping'][$this->destinyModuleModel->getId()] as $destinyField => $sourceField) {
							$columnsToCheck[$destinyField] = $this->textParserValues[$groupBy][$sourceField];
						}
					}
					if ($query->where($columnsToCheck)->exists()) {
						unset($this->cleanRecordModels[$groupBy]);
					}
				}
			}
		}
	}
}
