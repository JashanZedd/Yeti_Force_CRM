<?php

namespace App\TextParser;

/**
 * Products table long version class.
 *
 * @copyright YetiForce Sp. z o.o.
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Sołek <a.solek@yetiforce.com>
 */
class ProductsTableLongVersion extends Base
{
	/** @var string Class name */
	public $name = 'LBL_PRODUCTS_TABLE_LONG_VERSION';

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
		$firstRow = current($inventoryRows);
		$baseCurrency = \Vtiger_Util_Helper::getBaseCurrency();
		if ($inventory->isField('currency')) {
			if (\count($firstRow) > 0 && $firstRow['currency'] !== null) {
				$currency = $firstRow['currency'];
			} else {
				$currency = $baseCurrency['id'];
			}
			$currencyData = \App\Fields\Currency::getById($currency);
			$currencySymbol = $currencyData['currency_symbol'];
		}
		if (\count($fields[1])) {
			$visibleFields = ['Name', 'Value', 'Quantity', 'UnitPrice', 'TotalPrice', 'Discount', 'NetPrice', 'Tax', 'GrossPrice'];
			$fieldsTextAlignRight = ['Value', 'Quantity', 'UnitPrice', 'TotalPrice', 'Discount', 'NetPrice', 'Tax', 'GrossPrice'];
			$html .= '<table style="width:100%;font-size:8px;border-collapse:collapse;">
				<thead>
					<tr>';
			foreach ($fields[1] as $field) {
				if ($field->isVisible() && in_array($field->getType(), $visibleFields) && ($field->getColumnName() !== 'subunit')) {
					$html .= '<th style="text-align:center;">' . \App\Language::translate($field->get('label'), $this->textParser->moduleName) . '</th>';
				}
			}
			$html .= '</tr>
				</thead>
				<tbody>';
			foreach ($inventoryRows as &$inventoryRow) {
				$html .= '<tr>';
				foreach ($fields[1] as $field) {
					if (!$field->isVisible() || !in_array($field->getType(), $visibleFields) || ($field->getColumnName() === 'subunit')) {
						continue;
					}
					if ($field->getType() === 'ItemNumber') {
						$html .= '<td style="border:1px solid #ddd;"><strong>' . $inventoryRow['seq'] . '</strong></tdtyle>';
					} elseif ($field->getColumnName() === 'ean') {
						$code = $inventoryRow[$field->getColumnName()];
						$html .= '<td style="border:1px solid #ddd;"><barcode code="' . $code . '" type="EAN13" size="0.5" height="0.5" class="barcode" /></tdtyle>';
					} elseif ($field->isVisible()) {
						$itemValue = $inventoryRow[$field->getColumnName()];
						$html .= '<td style="font-size:8px;border:1px solid #ddd;' . (in_array($field->getType(), $fieldsTextAlignRight) ? 'text-align:right ' : '') . '">';
						switch ($field->getTemplateName('DetailView', $this->textParser->moduleName)) {
							case 'DetailViewName.tpl':
								$html .= '<strong>' . $field->getDisplayValue($itemValue, [], true) . '</strong>';
								foreach ($fields[2] as $commentKey => $value) {
									$COMMENT_FIELD = $fields[2][$commentKey];
									$comment = $COMMENT_FIELD->getDisplayValue($inventoryRow[$COMMENT_FIELD->getColumnName()]);
									if ($comment) {
										$html .= '<br />' . $comment;
									}
								}
								break;
							case 'DetailViewBase.tpl':
								if ($field->getColumnName() === 'Quantity' || $field->getColumnName() === 'Value') {
									$html .= $field->getDisplayValue($itemValue);
								} else {
									$html .= $field->getDisplayValue($itemValue) . ' ' . $currencySymbol;
								}
								break;
							default:
								break;
						}
						$html .= '</td>';
					}
				}
				$html .= '</tr>';
			}
			$html .= '</tbody><tfoot><tr>';
			foreach ($fields[1] as $field) {
				if ($field->isVisible() && in_array($field->getType(), $visibleFields) && ($field->getColumnName() !== 'subunit')) {
					$html .= '<th style="text-align:right;">';
					if ($field->isSummary()) {
						$sum = 0;
						foreach ($inventoryRows as &$inventoryRow) {
							$sum += $inventoryRow[$field->getColumnName()];
						}
						$html .= \CurrencyField::convertToUserFormat($sum, null, true) . ' ' . $currencySymbol;
					}
					$html .= '</th>';
				}
			}
			$html .= '</tr></tfoot></table>';
		}
		return $html;
	}
}
