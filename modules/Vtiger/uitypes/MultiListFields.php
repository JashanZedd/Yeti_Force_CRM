<?php
/**
 * UIType multi list fields field file.
 *
 * @package UIType
 *
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

/**
 *  UIType multi list fields field class.
 */
class Vtiger_MultiListFields_UIType extends Vtiger_Base_UIType
{
	/**
	 * {@inheritdoc}
	 */
	public function validate($value, $isUserFormat = false)
	{
		$hashValue = \is_array($value) ? ',' . implode(',', $value) . ',' : $value;
		if (isset($this->validate[$hashValue]) || empty($value)) {
			return;
		}
		if (\is_string($value)) {
			$value = explode(',', $value);
		}
		if (!\is_array($value)) {
			throw new \App\Exceptions\Security('ERR_ILLEGAL_FIELD_VALUE||' . $this->getFieldModel()->getFieldName() . '||' . $this->getFieldModel()->getModuleName() . '||' . $value, 406);
		}
		foreach ($value as $item) {
			if (!\is_string($item)) {
				throw new \App\Exceptions\Security('ERR_ILLEGAL_FIELD_VALUE||' . $this->getFieldModel()->getFieldName() . '||' . $this->getFieldModel()->getModuleName() . '||' . $value, 406);
			}
			if ($item != strip_tags($item)) {
				throw new \App\Exceptions\Security('ERR_ILLEGAL_FIELD_VALUE||' . $this->getFieldModel()->getFieldName() . '||' . $this->getFieldModel()->getModuleName() . '||' . $value, 406);
			}
		}
		$this->validate[$hashValue] = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDbConditionBuilderValue($value, string $operator)
	{
		$values = [];
		if (!\is_array($value)) {
			$value = $value ? explode('##', $value) : [];
		}
		foreach ($value as $val) {
			$values[] = parent::getDbConditionBuilderValue($val, $operator);
		}
		return implode(',', $values);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDBValue($value, $recordModel = false)
	{
		if (empty($value)) {
			return null;
		}
		if (!\is_array($value)) {
			$value = [$value];
		}
		$value = implode(',', $value);
		return \App\Purifier::decodeHtml($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayValue($value, $record = false, $recordModel = false, $rawText = false, $length = false)
	{
		if (empty($value)) {
			return null;
		}
		$value = str_ireplace(',', ', ', trim($value, ','));
		$fieldValues = explode(',', $value);
		foreach ($fieldValues as $fieldValue) {
			$fieldData = explode('|', $fieldValue);
			$fieldData = array_map('trim', $fieldData);
			$moduleModel = Vtiger_Module_Model::getInstance($fieldData[0]);
			if ($moduleModel) {
				$translatedValues[] = App\Language::translate($fieldData[0], $fieldData[0]) . ' - ' . App\Language::translate($moduleModel->getFieldByName($fieldData[1])->getFieldLabel(), $fieldData[0]);
			}
		}
		return \App\Purifier::encodeHtml(implode(', ', $translatedValues));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEditViewDisplayValue($value, $recordModel = false)
	{
		return array_filter(explode(',', \App\Purifier::encodeHtml($value)));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTemplateName()
	{
		return 'Edit/Field/MultiListFields.tpl';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getListSearchTemplateName()
	{
		return 'List/Field/MultiListFields.tpl';
	}

	/**
	 * Returns template for operator.
	 *
	 * @param string $operator
	 *
	 * @return string
	 */
	public function getOperatorTemplateName(string $operator = '')
	{
		return 'ConditionBuilder/MultiListFields.tpl';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAllowedColumnTypes()
	{
		return ['text'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getQueryOperators()
	{
		return ['e', 'n', 'c', 'k', 'y', 'ny'];
	}

	/**
	 * Get picklist values.
	 *
	 * @return array
	 */
	public function getPicklistValues()
	{
		$params = $this->getFieldModel()->getFieldParams();
		$condition = ['and',
			['<>', 'vtiger_field.presence', 1]
		];
		if (isset($params['uitype'])) {
			$condition[] = ['uitype' => $params['uitype']];
		}
		if (isset($params['excludedModules'])) {
			$condition[] = ['not in', 'vtiger_tab.name', $params['excludedModules']];
		}
		if (isset($params['allowedModules'])) {
			$condition[] = ['vtiger_tab.name' => $params['allowedModules']];
		}
		$return = [];
		$query = (new App\Db\Query())->from('vtiger_field')
			->leftJoin('vtiger_tab', 'vtiger_tab.tabid = vtiger_field.tabid')
			->where($condition)
			->orderBy(['vtiger_tab.tabid' => \SORT_ASC, 'vtiger_field.sequence' => \SORT_ASC]);
		$dataReader = $query->createCommand()->query();
		while ($row = $dataReader->read()) {
			if (isset($params['keys'])) {
				$key = [];
				foreach ($params['keys'] as $column) {
					$key[] = $row[$column];
				}
				$key = implode('|', $key);
			} else {
				$key = $row['fieldid'];
			}
			$return[$key] = App\Language::translate($row['name'], $row['name']) . ' - ' . App\Language::translate($row['fieldlabel'], $row['name']);
		}
		$dataReader->close();
		return $return;
	}
}
