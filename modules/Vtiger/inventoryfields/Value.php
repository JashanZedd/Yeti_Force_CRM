<?php

/**
 * Inventory Value Field Class.
 *
 * @package   InventoryField
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Vtiger_Value_InventoryField extends Vtiger_Basic_InventoryField
{
	protected $name = 'Value';
	protected $defaultLabel = 'LBL_STRING';
	protected $columnName = 'value';
	protected $dbType = 'string';
	protected $onlyOne = false;

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayValue($value, $rawText = false)
	{
		$mapDetail = $this->getMapDetail(true);
		if ($mapDetail) {
			$value = $mapDetail->getDisplayValue($value, false, false, true);
		}
		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEditValue($value)
	{
		return \App\Purifier::encodeHtml($value);
	}
}
