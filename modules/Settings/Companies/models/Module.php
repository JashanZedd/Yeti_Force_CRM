<?php

/**
 * Companies module model class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Settings_Companies_Module_Model extends Settings_Vtiger_Module_Model
{
	public $baseTable = 's_yf_companies';
	public $baseIndex = 'id';
	public $listFields = ['name' => 'LBL_NAME', 'status' => 'LBL_STATUS', 'type' => 'LBL_TYPE', 'email' => 'LBL_EMAIL', 'city' => 'LBL_CITY', 'country' => 'LBL_COUNTRY', 'website' => 'LBL_WEBSITE'];
	/**
	 * List of fields in form.
	 *
	 * @var array
	 */
	public static $formFields = [
		'name' => 'LBL_NAME',
		'industry' => 'LBL_INDUSTRY',
		'city' => 'LBL_CITY',
		'country' => 'LBL_COUNTRY',
		'companysize' => 'LBL_COMPANYSIZE',
		'website' => 'LBL_WEBSITE',
		'spacer' => '',
		'newsletter' => 'LBL_YETIFORCE_NEWSLETTER',
		'firstname' => 'LBL_FIRSTNAME',
		'lastname' => 'LBL_LASTNAME',
		'email' => 'LBL_EMAIL'
	];
	public $name = 'Companies';

	/**
	 * Function to get the url for default view of the module.
	 *
	 * @return string URL
	 */
	public function getDefaultUrl()
	{
		return 'index.php?module=Companies&parent=Settings&view=List';
	}

	/**
	 * Function to get the url for create view of the module.
	 *
	 * @return string URL
	 */
	public function getCreateRecordUrl()
	{
		return 'index.php?module=Companies&parent=Settings&view=Edit';
	}

	/**
	 * Function to get the column names.
	 *
	 * @return array|false
	 */
	public static function getColumnNames()
	{
		$tableSchema = \App\Db::getInstance('admin')->getTableSchema('s_#__companies', true);
		if ($tableSchema) {
			return $tableSchema->getColumnNames();
		}
		return false;
	}

	public static function getIndustryList()
	{
		return array_merge(
			(new \App\Db\Query())->select(['industry'])->from('vtiger_industry')->column(), (new \App\Db\Query())->select(['subindustry'])->from('vtiger_subindustry')->column()
		);
	}

	/**
	 * Return list fields in form.
	 *
	 * @return string[]
	 */
	public static function getFormFields()
	{
		return static::$formFields;
	}
}
