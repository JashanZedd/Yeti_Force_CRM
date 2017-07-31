<?php

/**
 * UIType Category multipicklist
 * @package YetiForce.UIType
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 * @author Krzysztof Gastołek <krzysztof.gastolek@wars.pl>
 * @author Tomasz Kur <t.kur@yetiforce.com>
 * @author Radosław Skrzypczak <r.skrzypczak@yetiforce.com>
 */
class Vtiger_CategoryMultipicklist_UIType extends Vtiger_Tree_UIType
{

	/**
	 * Function to get the Display Value, for the current field type with given DB Insert Value
	 * @param string $tree
	 * @param int $record
	 * @param Vtiger_Record_Model $recordInstance
	 * @param boolean $rawText
	 * @return string
	 */
	public function getDisplayValue($tree, $record = false, $recordInstance = false, $rawText = false)
	{
		$fieldModel = $this->get('field');
		$template = $fieldModel->getFieldParams();
		$module = $fieldModel->getModuleName();

		if (empty($tree)) {
			return '';
		}
		$names = [];
		$trees = array_filter(explode(',', $tree));
		if (\App\Cache::has('TreeData', $template)) {
			$treeData = \App\Cache::get('TreeData', $template);
		} else {
			$treeData = (new \App\Db\Query())
				->select(['tree', 'name', 'parenttrre', 'depth', 'label'])
				->from('vtiger_trees_templates_data')
				->where(['templateid' => $template])
				->createCommand()
				->queryAllByGroup(1);
			\App\Cache::save('TreeData', $template, $treeData, \App\Cache::LONG);
		}

		foreach ($trees as $treeId) {
			if (isset($treeData[$treeId])) {
				$row = $treeData[$treeId];
				if ($row['depth'] > 0) {
					$parenttrre = $row['parenttrre'];
					$pieces = explode('::', $parenttrre);
					end($pieces);
					$parent = prev($pieces);
					$parentName = isset($treeData[$parent]) ? $treeData[$parent]['label'] : '';
					$parentName = '(' . \App\Language::translate($parentName, $module) . ') ';
					$names[] = $parentName . \App\Language::translate($row['label'], $module);
				} else {
					$names[] = \App\Language::translate($row['label'], $module);
				}
			}
		}
		return implode(', ', $names);
	}

	/**
	 * Function to get the DB Insert Value, for the current field type with given User Value
	 * @param mixed $value
	 * @param \Vtiger_Record_Model $recordModel
	 * @return string
	 */
	public function getDBValue($value, $recordModel = false)
	{
		if ($value) {
			$value = ",$value,";
		} elseif (is_null($value)) {
			$value = '';
		}
		return $value;
	}
}
