<?php

/**
 * UIType multi email Field Class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Adach <a.adach@yetiforce.com>
 */
class Vtiger_MultiEmail_UIType extends Vtiger_Email_UIType
{
	/**
	 * {@inheritdoc}
	 *
	 * @example validate('[{"e":"a.adach@yetiforce.com","o":0},{"e":"test@yetiforce.com","o":0}]');
	 */
	public function validate($value, $isUserFormat = false)
	{
		$rawValue = $value;
		if (isset($this->validate[$value]) || empty($value)) {
			return;
		}
		if (is_string($value)) {
			$value = \App\Json::decode($value);
		} elseif (!is_array($value)) {
			throw new \App\Exceptions\Security('ERR_ILLEGAL_FIELD_VALUE||' . $this->getFieldModel()->getFieldName() . '||' . $value, 406);
		}
		foreach ($value as $item) {
			if (!is_array($item) || !array_key_exists('e', $item)) {
				throw new \App\Exceptions\Security('ERR_ILLEGAL_FIELD_VALUE||' . $this->getFieldModel()->getFieldName() . '||' . $value, 406);
			}
			if (!filter_var($item['e'], FILTER_VALIDATE_EMAIL) || $item['e'] !== filter_var($item['e'], FILTER_SANITIZE_EMAIL)) {
				throw new \App\Exceptions\Security('ERR_ILLEGAL_FIELD_VALUE||' . $this->getFieldModel()->getFieldName() . '||' . $value, 406);
			}
		}
		$this->validate[$rawValue] = true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDBValue($value, $recordModel = false)
	{
		return \App\Json::encode($value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDisplayValue($value, $record = false, $recordModel = false, $rawText = false, $length = false)
	{
		if (empty($value)) {
			return '';
		}
		$value = \App\Json::decode($value);
		if (empty($value)) {
			return '';
		}
		$emails = [];
		foreach ($value as $item) {
			$emails[] = parent::getDisplayValue($item['e'], $record, $recordModel, $rawText, $length);
		}
		return implode(',', $emails);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEditViewDisplayValue($value, $recordModel = false)
	{
		if (empty($value)) {
			return '';
		}
		$value = \App\Json::decode($value);
		if (empty($value)) {
			return '';
		}
		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getListViewDisplayValue($value, $record = false, $recordModel = false, $rawText = false)
	{
		return $this->getDisplayValue($value, $record, $recordModel, $rawText);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTemplateName()
	{
		return 'Edit/Field/MultiEmail.tpl';
	}
}
