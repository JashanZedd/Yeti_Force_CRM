<?php

/**
 * Inventory Name Field Class
 * @package YetiForce.Fields
 * @license licenses/License.html
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Vtiger_Name_InventoryField extends Vtiger_Basic_InventoryField
{

	protected $name = 'Name';
	protected $defaultLabel = 'LBL_ITEM_NAME';
	protected $columnName = 'name';
	protected $dbType = 'int(19) NOT NULL DEFAULT 0';
	protected $params = ['modules', 'limit'];

	/**
	 * Getting value to display
	 * @param type $value
	 * @return type
	 */
	public function getDisplayValue($value)
	{
		if ($value != 0)
			return Vtiger_Functions::getCRMRecordLabel($value);
		return '';
	}

	/**
	 * Getting value to display
	 * @return array
	 */
	public function modulesValues()
	{
		$modules = Vtiger_Module_Model::getAll([0], [], true);
		foreach ($modules AS $module) {
			$modulesNames[] = ['module' => $module->getName(), 'name' => $module->getName(), 'id' => $module->getName()];
		}
		return $modulesNames;
	}

	/**
	 * Getting value to display
	 * @return array
	 */
	public function limitValues()
	{
		return Vtiger_InventoryLimit_UIType::getLimits();
	}
}
