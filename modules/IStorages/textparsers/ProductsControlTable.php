<?php

/**
 * IStorages storage products table parser class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class IStorages_ProductsControlTable_Textparser extends \App\TextParser\Base
{
	/** @var string Class name */
	public $name = 'LBL_PRODUCTS_CONTROL_TABLE';

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
		$relationModuleName = 'Products';
		$relationListView = \Vtiger_RelationListView_Model::getInstance($this->textParser->recordModel, $relationModuleName);
		$pagingModel = new \Vtiger_Paging_Model();
		$pagingModel->set('limit', 0);
		$productModel = $relationListView->getRelatedModuleModel();
		$entries = $relationListView->getEntries($pagingModel);
		if ($entries) {
			$html .= '<table border="1" class="products-table" style="border-collapse:collapse;width:100%;"><thead><tr>';
			$columns = [];
			$headerStyle = 'font-size:9px;padding:0px 4px;text-align:center; width: 33.333%;';
			$bodyStyle = 'font-size:8px;border:1px solid #ddd;padding:0px 4px;';
			foreach (['productname', 'qtyinstock', 'qty_per_unit'] as $fieldName) {
				$fieldModel = $productModel->getFieldByName($fieldName);
				if (!$fieldModel || !$fieldModel->isActiveField()) {
					continue;
				}
				$columns[$fieldName] = $fieldModel;
				$html .= "<th style=\"{$headerStyle}\">" . \App\Language::translate($fieldModel->getFieldLabel(), $relationModuleName) . '</th>';
			}
			$html .= '</tr></thead><tbody>';
			foreach ($entries as $entry) {
				$html .= '<tr>';
				$entryId = $entry->getId();
				$entryRecordModel = \Vtiger_Record_Model::getInstanceById($entryId, $relationModuleName);
				foreach ($columns as $header) {
					$name = $header->getName();
					if ('productname' === $name) {
						$html .= "<td style=\"{$bodyStyle}\">" . $entry->getDisplayValue($name) . '</td>';
					}
				}
				$html .= "<td style=\"{$bodyStyle}\">" . \App\Fields\Double::formatToDisplay($entryRecordModel->get('qtyinstock'), false) . '</td>';
				$html .= "<td style=\"{$bodyStyle}\">" . \App\Fields\Double::formatToDisplay($entryRecordModel->get('qty_per_unit'), false) . '</td>';
				$html .= '</tr>';
			}
			$html .= '</tbody></table>';
		}
		return $html;
	}
}
