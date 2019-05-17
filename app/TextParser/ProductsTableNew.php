<?php

namespace App\TextParser;

/**
 * Products table new class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class ProductsTableNew extends Base
{
	/** @var string Class name */
	public $name = 'LBL_PRODUCTS_TABLE_NEW';

	/** @var mixed Parser type */
	public $type = 'pdf';

	/**
	 * Process.
	 *
	 * @return string
	 */
	public function process()
	{
		$html = '';
		if (!$this->textParser->recordModel->getModule()->isInventory()) {
			return $html;
		}
		$inventory = \Vtiger_Inventory_Model::getInstance($this->textParser->moduleName);
		$fields = $inventory->getFieldsByBlocks();
		$inventoryRows = $this->textParser->recordModel->getInventoryData();
		$baseCurrency = \Vtiger_Util_Helper::getBaseCurrency();
		$firstRow = current($inventoryRows);
		if ($inventory->isField('currency')) {
			if (!empty($firstRow) && null !== $firstRow['currency']) {
				$currency = $firstRow['currency'];
			} else {
				$currency = $baseCurrency['id'];
			}
			$currencyData = \App\Fields\Currency::getById($currency);
			$currencySymbol = $currencyData['currency_symbol'];
		}
		if (!empty($fields[1])) {
			$fieldsTextAlignRight = ['Unit', 'TotalPrice', 'Tax', 'MarginP', 'Margin', 'Purchase', 'Discount', 'NetPrice', 'GrossPrice', 'UnitPrice', 'Quantity'];
			$fieldsWithCurrency = ['TotalPrice', 'Purchase', 'NetPrice', 'GrossPrice', 'UnitPrice', 'Discount', 'Margin', 'Tax'];
			$html .= '<table class="products-table-new" style="border-collapse:collapse;width:100%">
				<thead>
					<tr>';
			foreach ($fields[1] as $field) {
				if ($field->isVisible()) {
					$html .= '<th class="col-type-' . $field->getType() . '">' . \App\Language::translate($field->get('label'), $this->textParser->moduleName) . '</th>';
				}
			}
			$html .= '</tr></thead><tbody>';
			$counter = 1;
			foreach ($inventoryRows as $inventoryRow) {
				$html .= '<tr class="row-' . $counter . '">';
				foreach ($fields[1] as $field) {
					if (!$field->isVisible()) {
						continue;
					}
					if ('ItemNumber' === $field->getType()) {
						$html .= '<td class="col-type-ItemNumber" style="font-weight:bold;">' . $counter++ . '</td>';
					} elseif ('ean' === $field->getColumnName()) {
						$code = $inventoryRow[$field->getColumnName()];
						$html .= '<td class="col-type-barcode"><div data-barcode="EAN13" data-code="' . $code . '" data-size="1" data-height="16"></div></td>';
					} else {
						$itemValue = $inventoryRow[$field->getColumnName()];
						$html .= '<td class="col-type-' . $field->getType() . '" style="font-size:8px;border:1px solid #ddd;padding:0px 4px;' . (in_array($field->getType(), $fieldsTextAlignRight) ? 'text-align:right;' : '') . '">';
						if ('Name' === $field->getType()) {
							$html .= '<strong>' . $field->getDisplayValue($itemValue, $inventoryRow) . '</strong>';
							foreach ($inventory->getFieldsByType('Comment') as $commentField) {
								if ($commentField->isVisible() && ($value = $inventoryRow[$commentField->getColumnName()])) {
									$comment = $commentField->getDisplayValue($value, $inventoryRow);
									if ($comment) {
										$html .= '<br />' . $comment;
									}
								}
							}
						} elseif (\in_array($field->getType(), $fieldsWithCurrency, true)) {
							$html .= \CurrencyField::appendCurrencySymbol($field->getDisplayValue($itemValue, $inventoryRow), $currencySymbol);
						} else {
							$html .= $field->getDisplayValue($itemValue, $inventoryRow);
						}
						$html .= '</td>';
					}
				}
				$html .= '</tr>';
			}
			$html .= '</tbody><tfoot><tr>';
			foreach ($fields[1] as $field) {
				if ($field->isVisible()) {
					$html .= '<th class="col-type-' . $field->getType() . '" style="padding:0px 4px;text-align:right;">';
					if ($field->isSummary()) {
						$sum = 0;
						foreach ($inventoryRows as $inventoryRow) {
							$sum += $inventoryRow[$field->getColumnName()];
						}
						$html .= \CurrencyField::appendCurrencySymbol(\CurrencyField::convertToUserFormat($sum, null, true), $currencySymbol);
					}
					$html .= '</th>';
				}
			}
			$html .= '</tr></tfoot></table>';
		}
		return $html;
	}
}
