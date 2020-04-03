<?php

/**
 * Field base map.
 *
 * @package Integration
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Dudek <a.dudek@yetiforce.com>
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace App\Integrations\Magento\Synchronizator\Maps;

abstract class Base
{
	/**
	 * Synchronizer.
	 *
	 * @var \App\Integrations\Magento\Synchronizator\Base
	 */
	protected $synchronizer;
	/**
	 * Fields which are not exist in Magento but needed in YetiForce.
	 *
	 * @var string[]
	 */
	public static $additionalFieldsCrm = [];
	/**
	 * Mapped fields.
	 *
	 * @var string[]
	 */
	public static $mappedFields = [];
	/**
	 * Mapped fields type.
	 *
	 * @var string[]
	 */
	public static $fieldsType = [
		'salutationtype' => 'map',
		'addresslevel1a' => 'country',
		'addresslevel1b' => 'country',
	];
	/**
	 * Fields default value.
	 *
	 * @var string[]
	 */
	public static $fieldsDefaultValue = [];

	/**
	 * Fields which can not be updated.
	 *
	 * @var string[]
	 */
	public static $nonEditableFields = [];
	/**
	 * Data from Magento.
	 *
	 * @var array
	 */
	public $data = [];
	/**
	 * Data from YetiForce.
	 *
	 * @var string[]
	 */
	public $dataCrm = [];
	/**
	 * Mapped billing fields.
	 *
	 * @var string[]
	 */
	public static $billingFields = [
		'addresslevel1a' => 'country_id',
		'addresslevel2a' => 'region|region',
		'addresslevel5a' => 'city',
		'addresslevel7a' => 'postcode',
		'addresslevel8a' => 'street|0',
		'buildingnumbera' => 'street|1',
		'first_name_a' => 'firstname',
		'last_name_a' => 'lastname',
		'phone_a' => 'telephone',
		'email_a' => 'email',
		'vat_id_a' => 'vat_id',
		'company_name_a' => 'company',
		'phone' => 'telephone',
		'mobile' => 'fax',
	];
	/**
	 * Mapped shipping fields.
	 *
	 * @var string[]
	 */
	public static $shippingFields = [
		'addresslevel1b' => 'country_id',
		'addresslevel2b' => 'region|region',
		'addresslevel5b' => 'city',
		'addresslevel7b' => 'postcode',
		'addresslevel8b' => 'street|0',
		'buildingnumberb' => 'street|1',
		'first_name_b' => 'firstname',
		'last_name_b' => 'lastname',
		'phone_b' => 'telephone',
		'email_b' => 'email',
		'vat_id_b' => 'vat_id',
		'company_name_b' => 'company',
		'phone' => 'telephone',
		'mobile' => 'fax',
	];

	/**
	 * Contacts_gender map.
	 *
	 * @var string[]
	 */
	public static $salutationtype = [
		'1' => 'Mr.',
		'2' => 'Mrs.',
	];

	/**
	 * Constructor.
	 *
	 * @param \App\Integrations\Magento\Synchronizator\Base $synchronizer
	 */
	public function __construct(\App\Integrations\Magento\Synchronizator\Base $synchronizer)
	{
		$this->synchronizer = $synchronizer;
	}

	/**
	 * Return YetiForce field name.
	 *
	 * @param string $name
	 *
	 * @return mixed|string
	 */
	public function getFieldNameCrm(string $name)
	{
		return array_flip(static::$mappedFields)[$name] ?? $name;
	}

	/**
	 * Return fields list.
	 *
	 * @param bool $onEdit
	 *
	 * @return array
	 */
	public function getFields(bool $onEdit = false): array
	{
		if ($onEdit) {
			$fields = array_diff(static::$mappedFields, static::$nonEditableFields);
		} else {
			$fields = static::$mappedFields;
		}
		return $fields;
	}

	/**
	 * Return additional YetiForce fields list.
	 *
	 * @return array
	 */
	public function getAdditionalFieldsCrm(): array
	{
		return static::$additionalFieldsCrm;
	}

	/**
	 * Set data.
	 *
	 * @param array $data
	 */
	public function setData(array $data): void
	{
		$this->data = $data;
	}

	/**
	 * Set data YetiForce.
	 *
	 * @param array $data
	 */
	public function setDataCrm(array $data): void
	{
		$this->dataCrm = $data;
	}

	/**
	 * Return parsed data in YetiForce format.
	 *
	 * @param bool $onEdit
	 *
	 * @return array
	 */
	public function getDataCrm(bool $onEdit = false): array
	{
		$parsedData = [];
		foreach ($this->getFields($onEdit) as $fieldCrm => $field) {
			$parsedData[$fieldCrm] = $this->getFieldValue($field) ?? null;
		}
		if (!$onEdit) {
			foreach ($this->getAdditionalFieldsCrm() as $name => $value) {
				$parsedData[$name] = !empty($value) ? $value : $this->getFieldValue($name);
			}
		}
		return $parsedData;
	}

	/**
	 * Get field value from Magento.
	 *
	 * @param string $fieldName
	 *
	 * @return array|mixed
	 */
	public function getFieldValue(string $fieldName)
	{
		$parsedFieldName = $this->getFieldNameCrm($fieldName);
		$methodName = 'getCrm' . \ucfirst($parsedFieldName);
		$fieldLevels = explode('|', $fieldName);
		if (!\method_exists($this, $methodName)) {
			$fieldParsed = $this->data;
			if (\count($fieldLevels) > 1) {
				if ('custom_attributes' === $fieldLevels[0]) {
					$fieldParsed = $this->getCustomAttributeValue(end($fieldLevels));
				} else {
					$elements = \count($fieldLevels);
					foreach ($fieldLevels as $level => $fieldLevel) {
						if (isset($fieldParsed[$fieldLevel])) {
							$fieldParsed = $fieldParsed[$fieldLevel];
						} else {
							if ($elements !== $level + 1) {
								$fieldParsed = '';
							}
							break;
						}
					}
				}
			} else {
				$fieldParsed = $fieldParsed[$fieldName] ?? '';
			}
			$fieldParsedValue = $fieldParsed;
			if (null !== $fieldParsed && isset(static::$fieldsType[$parsedFieldName])) {
				switch (static::$fieldsType[$parsedFieldName]) {
					case 'map':
						$fieldParsed = static::${$parsedFieldName}[$fieldParsed] ?? null;
						if (null === $fieldParsed) {
							\App\Log::warning("No value in mapping (map)|name: $parsedFieldName|value: $fieldParsedValue", 'Updates');
						}
						break;
					case 'implode':
						$fieldParsed = implode(', ', $fieldParsed);
						break;
					case 'country':
						$fieldParsed = \App\Fields\Country::getCountryName($fieldParsed);
						break;
					case 'date':
						$fieldParsed = \App\Fields\Date::formatToDb($fieldParsed, true);
						break;
					case 'datetime':
						$fieldParsed = \App\Fields\DateTime::formatToDb($fieldParsed, true);
						break;
				}
			} else {
				$fieldParsed = !\is_array($fieldParsed) ? $fieldParsed : null;
				if (null === $fieldParsed) {
					$name = $fieldLevel ?? $fieldName;
					\App\Log::info("No value in mapping|CRM: $parsedFieldName|Magento: $name|" . PHP_EOL . print_r($fieldParsedValue, true), 'Updates');
				}
			}
		} else {
			$fieldParsed = $this->{$methodName}();
		}
		return $fieldParsed;
	}

	/**
	 * Get custom attribute value.
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function getCustomAttributeValue(string $name)
	{
		$value = '';
		$customAttributes = $this->data['custom_attributes'];
		if (!empty($customAttributes)) {
			foreach ($customAttributes as $customAttribute) {
				if ($name === $customAttribute['attribute_code']) {
					$value = $customAttribute['value'];
				}
			}
		}
		return $value;
	}

	/**
	 * Parse phone number.
	 *
	 * @param string $fieldName
	 * @param array  $parsedData
	 *
	 * @return array
	 */
	public function parsePhone(string $fieldName, array $parsedData): array
	{
		if (\App\Config::main('phoneFieldAdvancedVerification', false)) {
			$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
			try {
				$swissNumberProto = $phoneUtil->parse($parsedData[$fieldName]);
				$international = $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
				var_dump($international);
			} catch (\libphonenumber\NumberParseException $e) {
				$international = false;
			}
			if ($international) {
				$parsedData[$fieldName] = $international;
			} else {
				$parsedData[$fieldName . '_extra'] = $parsedData[$fieldName];
				unset($parsedData[$fieldName]);
			}
		}
		return $parsedData;
	}

	/**
	 * Return address data.
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	public function getAddressDataCrm(string $type): array
	{
		$addressData = [];
		foreach ($this->getAddressFieldsCrm($type) as $fieldNameCrm => $fieldName) {
			$addressData[$fieldNameCrm] = $this->getAddressFieldValue($type, $fieldNameCrm, $fieldName);
		}
		return $addressData;
	}

	/**
	 * Return address fields crm.
	 *
	 * @param string $type
	 *
	 * @return array
	 */
	public function getAddressFieldsCrm(string $type): array
	{
		$fieldsType = $type . 'Fields';
		if (!empty(self::${$fieldsType})) {
			$fields = self::${$fieldsType};
		}
		return $fields ?? [];
	}

	/**
	 * Get address field value from Magento.
	 *
	 * @param string $type
	 * @param string $fieldNameCrm
	 * @param string $fieldName
	 *
	 * @return array|mixed
	 */
	public function getAddressFieldValue(string $type, string $fieldNameCrm, string $fieldName)
	{
		$methodName = 'getCrm' . \ucfirst($fieldNameCrm);
		if (!empty($fieldParsed = $this->getAddressDataByType($type))) {
			if (\method_exists($this, $methodName)) {
				$fieldParsed = $this->{$methodName}();
			} else {
				$fieldLevels = explode('|', $fieldName);
				if (($elements = \count($fieldLevels)) > 1) {
					foreach ($fieldLevels as $level => $fieldLevel) {
						if (isset($fieldParsed[$fieldLevel])) {
							$fieldParsed = $fieldParsed[$fieldLevel];
						} else {
							if ($elements !== $level + 1) {
								$fieldParsed = '';
							}
							break;
						}
					}
				} else {
					$fieldParsed = $fieldParsed[$fieldName] ?? '';
				}
				if (null !== $fieldParsed && isset(static::$fieldsType[$fieldNameCrm])) {
					switch (static::$fieldsType[$fieldNameCrm]) {
						case 'implode':
							$fieldParsed = implode(', ', $fieldParsed);
							break;
						case 'country':
							$fieldParsed = \App\Fields\Country::getCountryName($fieldParsed);
							break;
					}
				} else {
					$fieldParsed = \is_array($fieldParsed) ? null : $fieldParsed;
				}
			}
		} else {
			$fieldParsed = '';
		}
		return $fieldParsed;
	}

	/**
	 * Get given type address data.
	 *
	 * @param string $addressType
	 *
	 * @return array
	 */
	public function getAddressDataByType(string $addressType)
	{
		$data = [];
		$addressName = 'default_' . $addressType;
		foreach ($this->data['addresses'] as $address) {
			if (isset($this->data[$addressName]) && $address['id'] == $this->data[$addressName]) {
				$data = $address;
			}
		}
		return $data ?? current($this->data['addresses']) ?? [];
	}

	/**
	 * Get birthday.
	 *
	 * @return string|null
	 */
	public function getCrmBirthday()
	{
		$dob = $this->data['dob'] ?? $this->data['customer_dob'] ?? false;
		if (!$dob || '0000-00-00' === $dob) {
			return null;
		}
		return $dob;
	}
}
