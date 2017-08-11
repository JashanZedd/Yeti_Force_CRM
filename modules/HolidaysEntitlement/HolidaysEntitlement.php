<?php
/**
 * HolidaysEntitlement class
 * @package YetiForce.CRMEntity
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 */
include_once 'modules/Vtiger/CRMEntity.php';

/**
 * Class HolidaysEntitlement
 */
class HolidaysEntitlement extends Vtiger_CRMEntity
{

	/**
	 * Table name
	 * @var string
	 */
	public $table_name = 'vtiger_holidaysentitlement';

	/**
	 * Table index
	 * @var string
	 */
	public $table_index = 'holidaysentitlementid';

	/**
	 * Column fields
	 * @var array
	 */
	public $column_fields = [];

	/**
	 * Mandatory table for supporting custom fields.
	 * @var array
	 */
	public $customFieldTable = ['vtiger_holidaysentitlementcf', 'holidaysentitlementid'];

	/**
	 * Mandatory for Saving, Include tables related to this module.
	 * @var array
	 */
	public $tab_name = ['vtiger_crmentity', 'vtiger_holidaysentitlement', 'vtiger_holidaysentitlementcf'];

	/**
	 * Mandatory for Saving, Include tablename and tablekey columnname here.
	 * @var array
	 */
	public $tab_name_index = [
		'vtiger_crmentity' => 'crmid',
		'vtiger_holidaysentitlement' => 'holidaysentitlementid',
		'vtiger_holidaysentitlementcf' => 'holidaysentitlementid'];

	/**
	 * Mandatory for Listing (Related listview)
	 * @var array
	 */
	public $list_fields = [
		/* Format: Field Label => Array(tablename, columnname) */
// tablename should not have prefix 'vtiger_'
		'LBL_NO' => ['holidaysentitlement', 'holidaysentitlement_no'],
		'LBL_EMPLOYEE' => ['holidaysentitlement', 'ossemployeesid'],
		'Assigned To' => ['crmentity', 'smownerid']
	];

	/**
	 * List fields name
	 * @var array
	 */
	public $list_fields_name = [
		/* Format: Field Label => fieldname */
		'LBL_NO' => 'holidaysentitlement_no',
		'LBL_EMPLOYEE' => 'ossemployeesid',
		'Assigned To' => 'assigned_user_id',
	];

	/**
	 *  List of fields in the RelationListView
	 * @var string[]
	 */
	public $relationFields = ['holidaysentitlement_no', 'ossemployeesid', 'assigned_user_id'];

	/**
	 * Make the field link to detail view
	 * @var string
	 */
	public $list_link_field = 'subject';

	/**
	 * For Popup listview and UI type support
	 * @var array
	 */
	public $search_fields = [
		/* Format: Field Label => Array(tablename, columnname) */
// tablename should not have prefix 'vtiger_'
		'LBL_NO' => ['holidaysentitlement', 'holidaysentitlement_no'],
		'LBL_EMPLOYEE' => ['holidaysentitlement', 'ossemployeesid'],
		'Assigned To' => ['crmentity', 'assigned_user_id'],
	];

	/**
	 * Search fields name
	 * @var array
	 */
	public $search_fields_name = [
		/* Format: Field Label => fieldname */
		'LBL_NO' => 'holidaysentitlement_no',
		'LBL_EMPLOYEE' => 'ossemployeesid',
		'Assigned To' => 'assigned_user_id',
	];

	/**
	 * For Popup window record selection
	 * @var array
	 */
	public $popup_fields = ['ossemployeesid'];

	/**
	 * For Alphabetical search
	 * @var string
	 */
	public $def_basicsearch_col = 'ossemployeesid';

	/**
	 * Column value to use on detail view record text display
	 * @var string
	 */
	public $def_detailview_recname = 'ossemployeesid';

	/**
	 * Used when enabling/disabling the mandatory fields for the module. Refers to vtiger_field.fieldname values.
	 * @var array
	 */
	public $mandatory_fields = ['ossemployeesid', 'assigned_user_id', 'holidaysentitlement_year', 'days'];

	/**
	 * Default order by
	 * @var string
	 */
	public $default_order_by = '';

	/**
	 * Default sort order
	 * @var string
	 */
	public $default_sort_order = 'ASC';

	/**
	 * Invoked when special actions are performed on the module.
	 * @param string Module name
	 * @param string Event Type
	 */
	public function vtlib_handler($moduleName, $eventType)
	{
		if ($eventType === 'module.postinstall') {
			$moduleInstance = CRMEntity::getInstance('HolidaysEntitlement');
			\App\Fields\RecordNumber::setNumber($moduleName, 'HE', '1');
			\App\Db::getInstance()->createCommand()->update('vtiger_tab', ['customized' => 0], ['name' => 'HolidaysEntitlement'])->execute();
			$moduleInstance = vtlib\Module::getInstance('HolidaysEntitlement');
			$targetModule = vtlib\Module::getInstance('OSSEmployees');
			$targetModule->setRelatedList($moduleInstance, 'HolidaysEntitlement', ['ADD'], 'getDependentsList');
		} else if ($eventType === 'module.disabled') {

		} else if ($eventType === 'module.preuninstall') {

		} else if ($eventType === 'module.preupdate') {

		} else if ($eventType === 'module.postupdate') {

		}
	}
}
