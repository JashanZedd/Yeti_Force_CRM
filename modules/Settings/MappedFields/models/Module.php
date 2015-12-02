<?php

/**
 * Module Class for MappedFields Settings
 * @package YetiForce.Model
 * @license licenses/License.html
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
class Settings_MappedFields_Module_Model extends Settings_Vtiger_Module_Model
{

	protected $record = false;
	public $baseTable = 'a_yf_mapped_config';
	public $mappingTable = 'a_yf_mapped_fields';
	public $baseIndex = 'id';
	public $mappingIndex = 'mappedid';
	public $listFields = [
		'tabid' => 'LBL_MODULE',
		'reltabid' => 'LBL_REL_MODULE',
		'status' => 'LBL_STATUS'
	];
	public static $allFields = [
		'tabid',
		'reltabid',
		'status',
		'conditions',
		'permissions',
		'params'
	];
	public static $step1Fields = ['status', 'tabid', 'reltabid'];
	public static $step2Fields = ['source', 'target', 'default', 'type'];
	public static $step3Fields = ['conditions',];
	public static $step4Fields = ['permissions'];
	public $name = 'MappedFields';
	public $parent = 'Settings';

	public function getCreateRecordUrl()
	{
		return 'index.php?module=MappedFields&parent=Settings&view=Edit';
	}

	public function getImportViewUrl()
	{
		return 'index.php?module=MappedFields&parent=Settings&view=Import';
	}

	public function getRecord()
	{
		return $this->record;
	}

	/**
	 * Function to get the Module/Tab id
	 * @return <Number>
	 */
	public function getId()
	{
		return Vtiger_Functions::getModuleId($this->getName());
	}

	public static function getFieldsByStep($step = 1)
	{
		switch ($step) {
			case 4:
				return self::$step4Fields;
			case 3:
				return self::$step3Fields;
			case 2:
				return self::$step2Fields;
			case 1:
			default:
				return self::$step1Fields;
		}
	}

	/**
	 * Function to get the Restricted Ui Types
	 * @return <array> Restricted ui types
	 */
	public function getRestrictedUitypes()
	{
		return [4, 51, 52, 57, 58, 69, 70];
	}

	/**
	 * Function to get the Restricted Ui Types
	 * @return <array> Restricted ui types
	 */
	public function getRecordId()
	{
		return $this->record->getId();
	}

	public static function getSupportedModules()
	{
		$restrictedModules = ['OSSMailView'];
		$moduleModels = Vtiger_Module_Model::getAll([0, 2]);
		$supportedModuleModels = [];
		foreach ($moduleModels as $tabId => $moduleModel) {
			if ($moduleModel->isEntityModule() && !in_array($moduleModel->getName(), $restrictedModules)) {
				$supportedModuleModels[$tabId] = $moduleModel;
			}
		}
		return $supportedModuleModels;
	}

	/**
	 * Function to get instance
	 * @return <Settings_MappedFields_Module_Model>
	 */
	public static function getCleanInstance($moduleName = 'Vtiger')
	{
		$log = vglobal('log');
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '(' . $moduleName . ') method ...');
		$handlerClass = Vtiger_Loader::getComponentClassName('Model', 'MappedFields', $moduleName);
		$mf = new $handlerClass();
		$data = [];
		$fields = self::getFieldsByStep();
		foreach ($fields as $field) {
			$data[$field] = '';
		}
		$mf->setData($data);
		$instance = new self();
		$instance->record = $mf;
		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' method ...');
		return $instance;
	}

	/**
	 * Function to get the value for a given key
	 * @param $key
	 * @return Value for the given key
	 */
	public function get($key)
	{
		return $this->record->get($key);
	}

	/**
	 * Function to get instance of module
	 * @param <String> $moduleName
	 * @return <Settings_MappedFields_Module_Model>
	 */
	public static function getInstance($moduleName)
	{
		$log = vglobal('log');
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '(' . $moduleName . ') method ...');
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if ($moduleModel) {
			$objectProperties = get_object_vars($moduleModel);
			$moduleModel = new self();
			foreach ($objectProperties as $properName => $propertyValue) {
				$moduleModel->$properName = $propertyValue;
			}
		}
		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' method ...');
		return $moduleModel;
	}

	public static function getInstanceById($recordId, $moduleName = 'Vtiger')
	{
		$log = vglobal('log');
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '(' . $recordId . ',' . $moduleName . ') method ...');
		$instance = new self();
		$instance->record = Vtiger_MappedFields_Model::getInstanceById($recordId, $moduleName);
		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' method ...');
		return $instance;
	}

	/**
	 * Function to get mapping details
	 * @return <Array> list of mapping details
	 */
	public function getMapping()
	{
		return $this->record->getMapping();
	}

	/**
	 * Function to set mapping details
	 * @return instance
	 */
	public function setMapping($mapp = [])
	{
		$this->record->setMapping($mapp);
		return $this;
	}

	/**
	 * Function to set mapping details
	 * @return instance
	 */
	public static function getSpecialFields()
	{
		$db = PearDatabase::getInstance();
		$fields = ['id' => ['name' => 'id', 'id' => 'id', 'fieldDataType' => 'reference', 'label' => 'LBL_SELF_ID', 'typeofdata' => 'SELF']];
		$models = [];
		foreach ($fields as $fieldName => $data) {
			$fieldInstane = Settings_MappedFields_Field_Model::fromArray($data);
			$models[$fieldName] = $fieldInstane;
		}
		return $models;
	}

	/**
	 * Function returns fields of module
	 * @return <Array of Vtiger_Field>
	 */
	public function getFields($source = false)
	{
		$log = vglobal('log');
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '() method ...');
		$moduleModel = Vtiger_Module_Model::getInstance($this->getName());
		$moduleMeta = $moduleModel->getModuleMeta();
		$moduleFields = $moduleMeta->getAccessibleFields($this->getName());
		$fields = [];
		foreach ($moduleFields as $fieldName => $fieldInstance) {
			if ($moduleMeta->isEditableField($fieldInstance) && !in_array($fieldInstance->getUIType(), $this->getRestrictedUitypes())) {
				$blockName = $fieldInstance->getBlockName();
				if (!$blockName) {
					$blockName = 'LBL_NOT_ASSIGNET_TO_BLOCK';
				}
				$fields[$blockName][$fieldInstance->getFieldId()] = Settings_MappedFields_Field_Model::getInstanceFromWebserviceFieldObject($fieldInstance);
			}
		}
		if ($source) {
			foreach ($this->getSpecialFields() as $fieldName => $fieldInstance) {
				$fields['LBL_NOT_ASSIGNET_TO_BLOCK'][$fieldName] = $fieldInstance;
			}
		}

		$isInventory = $moduleModel->isInventory();
		if ($isInventory) {
			$inventoryFieldModel = Vtiger_InventoryField_Model::getInstance($this->getName());
			$inventoryFields = $inventoryFieldModel->getFields();
			$blockName = 'LBL_ADVANCED_BLOCK';
			foreach ($inventoryFields as $field) {
				$fields[$blockName][$field->get('columnname')] = Settings_MappedFields_Field_Model::getInstanceFromInventoryFieldObject($field);
			}
		}
		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' method ...');
		return $fields;
	}

	public function deleteMapping($mappedIds)
	{
		$log = vglobal('log');
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '() method ...');
		$db = PearDatabase::getInstance();
		if (!is_array($mappedIds)) {
			$mappedIds = [$mappedIds];
		}
		$db->delete($this->mappingTable, $this->mappingIndex . ' IN (' . generateQuestionMarks($mappedIds) . ');', $mappedIds);
		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' method ...');
	}

	public function delete()
	{
		$db = PearDatabase::getInstance();
		return $db->delete($this->baseTable, '`' . $this->baseIndex . '` = ?', [$this->getRecordId()]);
	}

	public function importsAllowed()
	{
		$db = PearDatabase::getInstance();
		$query = 'SELECT 1 FROM `' . $this->baseTable . '` WHERE `tabid` = ? AND `reltabid` = ? ;';
		$result = $db->pquery($query, [$this->get('tabid'), $this->get('reltabid')]);
		return $result->rowCount();
	}

	public function save($saveMapping = false)
	{
		$log = vglobal('log');
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '(' . $saveMapping . ') method ...');
		$db = PearDatabase::getInstance();
		$fields = self::$allFields;
		$params = [];
		foreach ($fields as $field) {
			$value = $this->record->get($field);
			if (in_array($field, ['conditions', 'params'])) {
				$params[$field] = Zend_Json::encode($value);
			} elseif (is_array($value)) {
				$params[$field] = implode(',', $value);
			} else {
				$params[$field] = $value;
			}
		}
		if (!$this->getRecordId()) {
			$db->insert($this->baseTable, $params);
			$this->record->set('id', $db->getLastInsertID());
		} else {
			$db->update($this->baseTable, $params, '`' . $this->baseIndex . '` = ?', [$this->getRecordId()]);
		}
		if ($saveMapping) {
			$stepFields = Settings_MappedFields_Module_Model::getFieldsByStep(2);
			$this->deleteMapping($this->getRecordId());
			foreach ($this->getMapping() as $mapp) {
				$params = [];
				$params[$this->mappingIndex] = $this->getRecordId();
				foreach ($stepFields as $name) {
					$params[$name] = $mapp[$name];
				}
				if ($params['source'] && $params['target']) {
					$db->insert($this->mappingTable, $params);
				}
			}
		}
		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' method ...');
		return $this->getRecordId();
	}

	/**
	 * Function transforms Advance filter to workflow conditions
	 */
	function transformAdvanceFilterToWorkFlowFilter()
	{
		$log = vglobal('log');
		$log->debug('Entering ' . __CLASS__ . '::' . __METHOD__ . '() method ...');
		$conditions = $this->get('conditions');
		$wfCondition = [];
		if (!empty($conditions)) {
			foreach ($conditions as $index => $condition) {
				$columns = $condition['columns'];
				if ($index == '1' && empty($columns)) {
					$wfCondition[] = array('fieldname' => '', 'operation' => '', 'value' => '', 'valuetype' => '',
						'joincondition' => '', 'groupid' => '0');
				}
				if (!empty($columns) && is_array($columns)) {
					foreach ($columns as $column) {
						$wfCondition[] = array('fieldname' => $column['columnname'], 'operation' => $column['comparator'],
							'value' => $column['value'], 'valuetype' => $column['valuetype'], 'joincondition' => $column['column_condition'],
							'groupjoin' => $condition['condition'], 'groupid' => $column['groupid']);
					}
				}
			}
		}
		$this->getRecord()->set('conditions', $wfCondition);
		$log->debug('Exiting ' . __CLASS__ . '::' . __METHOD__ . ' method ...');
	}
}
