<?php
/**
 * @package YetiForce.Model
 * @license licenses/License.html
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
class Settings_Inventory_Record_Model extends Vtiger_Base_Model
{

	public function __construct($values = [])
	{
		parent::__construct($values);
	}

	private $type;

	public function getId()
	{
		return $this->get('id');
	}

	public function getName()
	{
		return $this->get('name');
	}

	public function getValue()
	{
		return $this->get('value');
	}

	public function getStatus()
	{
		return $this->get('status');
	}

	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getCreateUrl()
	{
		return '?module=Inventory&parent=Settings&view=ModalAjax&type=' . $this->getType();
	}

	public function getEditUrl()
	{
		return '?module=Inventory&parent=Settings&view=ModalAjax&type=' . $this->getType() . '&id=' . $this->getId();
	}

	public static function getTableNameFromType($type)
	{
		$tablename = ['CreditLimits' => 'a_yf_inventory_limits', 'Taxes' => 'a_yf_taxes_global', 'Discounts' => 'a_yf_discounts_global'];
		return $tablename[$type];
	}

	public function save()
	{
		$db = PearDatabase::getInstance();
		$tablename = self::getTableNameFromType($this->getType());
		$id = $this->getId();

		if (!empty($id) && $tablename) {
			$query = 'UPDATE ' . $tablename . ' SET `name`=?,`value`=?,`status`=? WHERE id = ?';
			$params = array($this->getName(), $this->get('value'), $this->get('status'), $id);
			$db->pquery($query, $params);
		} else {
			$id = $this->add();
		}
		return $id;
	}

	/** 	Function used to add the tax type which will do database alterations
	 * 	@param string $taxlabel - tax label name to be added
	 * 	@param string $taxvalue - tax value to be added
	 *      @param string $sh - sh or empty , if sh passed then the tax will be added in shipping and handling related table
	 *      @return void
	 */
	public function add()
	{
		$adb = PearDatabase::getInstance();
		$tableName = self::getTableNameFromType($this->getType());
		if ($tableName) {
			$query = 'INSERT INTO `' . $tableName . '` (`status`,`value`,`name`) values(?,?,?)';
			$params = [$this->get('status'), $this->get('value'), $this->getName()];
			$adb->pquery($query, $params);
			return $adb->getLastInsertID();
		}
		throw new Error('Error occurred while adding value');
	}
	public function delete()
	{
		$adb = PearDatabase::getInstance();
		$tableName = self::getTableNameFromType($this->getType());
		if ($tableName) {
			$query = 'DELETE FROM `' . $tableName . '` WHERE id = ?;';
			$params = [$this->getId()];
			$adb->pquery($query, $params);
			return true;
		}
		throw new Error('Error occurred while deleting value');
	}

	public static function getDataAll($type)
	{
		$db = PearDatabase::getInstance();
		$recordList = [];
		$tableName = self::getTableNameFromType($type);
		
		if(!$tableName){
			return $recordList;
		}
		$query = 'SELECT * FROM ' . $tableName;
		$result = $db->query($query);
		while ($row = $db->fetch_array($result)) {
			$recordModel = new self();
			$recordModel->setData($row)->setType($type);
			$recordList[] = $recordModel;
		}
		return $recordList;
	}

	public static function getInstanceById($id, $type = '')
	{
		$db = PearDatabase::getInstance();
		$tableName = self::getTableNameFromType($type);

		if(!$tableName){
			return false;
		}
		$query = 'SELECT * FROM ' . $tableName . ' WHERE `id` = ?;';
		$result = $db->pquery($query, [$id]);
		$recordModel = new self();
		while ($row = $db->fetch_array($result)) {
			$recordModel->setData($row)->setType($type);
		}
		return $recordModel;
	}
	public static function checkDuplicate($label, $excludedIds = [], $type = '')
	{
		$db = PearDatabase::getInstance();
		if (!is_array($excludedIds)) {
			if (!empty($excludedIds)) {
				$excludedIds = [$excludedIds];
			} else {
				$excludedIds = [];
			}
		}
		$tableName = self::getTableNameFromType($type);
		$query = 'SELECT 1 FROM ' . $tableName . ' WHERE `name` = ?';
		$params = [$label];

		if (!empty($excludedIds)) {
			$query .= " AND `id` NOT IN (" . generateQuestionMarks($excludedIds) . ")";
			$params = array_merge($params, $excludedIds);
		}
		$result = $db->pquery($query, $params);

		return ($db->num_rows($result) > 0) ? true : false;
	}
}
