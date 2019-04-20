<?php

/**
 * Tax percent field.
 *
 * @package   InventoryField
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */

/**
 * Inventory TaxPercent Field Class.
 */
class Vtiger_TaxPercent_InventoryField extends Vtiger_Tax_InventoryField
{
	protected $type = 'TaxPercent';
	protected $defaultLabel = 'LBL_TAX_IN_PERCENT';
	protected $defaultValue = 0;
	protected $summationValue = false;
	protected $columnName = 'tax_percent';
	protected $dbType = 'decimal(12,8) DEFAULT 0';
	protected $maximumLength = '9999';
	protected $purifyType = \App\Purifier::NUMBER;
	/**
	 * @var array List of shared fields
	 */
	public $shared = ['taxparam' => 'tax'];

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayValue($value, array $rowData = [], bool $rawText = false)
	{
		return App\Fields\Double::formatToDisplay($value);
	}
}
