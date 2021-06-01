<?php
/**
 * Portal container - Get product records tree detail file.
 *
 * @package Api
 *
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Arkadiusz Adach <a.adach@yetiforce.com>
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace Api\Portal\Products;

/**
 * Portal container - Get product records tree detail class.
 */
class RecordsTree extends \Api\Portal\BaseModule\RecordsList
{
	/**
	 * Permission type.
	 *
	 * @var int
	 */
	private $permissionType;

	/**
	 * Is user permissions.
	 *
	 * @var bool
	 */
	private $isUserPermissions;

	/**
	 * Parent record model.
	 *
	 * @var \Vtiger_Record_Model
	 */
	private $parentRecordModel;

	/**
	 * Construct.
	 *
	 * @OA\GET(
	 *		path="/webservice/Portal/Products/RecordsTree",
	 *		summary="Get the list of records",
	 *		tags={"Products"},
	 *		security={
	 *			{"basicAuth" : {}, "ApiKeyAuth" : {}, "token" : {}}
	 *		},
	 *		@OA\Parameter(
	 *			name="x-raw-data",
	 *			description="Get rows limit, default: 0",
	 *			@OA\Schema(type="integer", enum={0, 1}),
	 *			in="header",
	 *			example=1,
	 *			required=false
	 *		),
	 *		@OA\Parameter(
	 *			name="x-row-limit",
	 *			description="Get rows limit, default: 1000",
	 *			@OA\Schema(type="integer"),
	 *			in="header",
	 *			example=1000,
	 *			required=false
	 *		),
	 *		@OA\Parameter(
	 *			name="x-row-offset",
	 *			description="Offset, default: 0",
	 *			@OA\Schema(type="integer"),
	 *			in="header",
	 *			example=0,
	 *			required=false
	 *		),
	 *		@OA\Parameter(
	 *			name="x-row-order-field",
	 *			description="Sets the ORDER BY part of the query record list",
	 *			@OA\Schema(type="string"),
	 *			in="header",
	 *			example="lastname",
	 *			required=false
	 *		),
	 *		@OA\Parameter(
	 *			name="x-row-order",
	 *			description="Sorting direction",
	 *			@OA\Schema(type="string", enum={"ASC", "DESC"}),
	 *			in="header",
	 *			example="DESC",
	 *			required=false
	 *		),
	 *		@OA\Parameter(
	 *			name="x-fields",
	 *			description="JSON array in the list of fields to be returned in response",
	 *			in="header",
	 *			required=false,
	 *			@OA\JsonContent(
	 *				type="array",
	 * 				@OA\Items(type="string"),
	 * 			)
	 *		),
	 *		@OA\Parameter(
	 *			name="x-condition",
	 * 			description="Conditions [Json format]",
	 *			in="header",
	 *			required=false,
	 *			@OA\JsonContent(
	 *				description="Conditions details",
	 *				type="object",
	 *				@OA\Property(property="fieldName", description="Field name", type="string", example="lastname"),
	 *				@OA\Property(property="value", description="Search value", type="string", example="Kowalski"),
	 *				@OA\Property(property="operator", description="Field operator", type="string", example="e"),
	 *				@OA\Property(property="group", description="Condition group if true is AND", type="boolean", example=true),
	 *			),
	 *		),
	 *		@OA\Parameter(
	 *			name="x-parent-id",
	 *			description="Parent record id",
	 *			@OA\Schema(type="integer"),
	 *			in="header",
	 *			example=5,
	 *			required=false
	 *		),
	 *		@OA\Response(
	 *			response=200,
	 *			description="List of consents",
	 *			@OA\JsonContent(ref="#/components/schemas/Products_RecordsList_ResponseBody"),
	 *			@OA\XmlContent(ref="#/components/schemas/Products_RecordsList_ResponseBody"),
	 *		),
	 *		@OA\Response(
	 *			response=400,
	 *			description="Incorrect json syntax: x-fields",
	 *			@OA\JsonContent(ref="#/components/schemas/Exception"),
	 *			@OA\XmlContent(ref="#/components/schemas/Exception"),
	 *		),
	 *		@OA\Response(
	 *			response=401,
	 *			description="No sent token, Invalid token, Token has expired",
	 *			@OA\JsonContent(ref="#/components/schemas/Exception"),
	 *			@OA\XmlContent(ref="#/components/schemas/Exception"),
	 *		),
	 *		@OA\Response(
	 *			response=403,
	 *			description="No permissions for module",
	 *			@OA\JsonContent(ref="#/components/schemas/Exception"),
	 *			@OA\XmlContent(ref="#/components/schemas/Exception"),
	 *		),
	 *		@OA\Response(
	 *			response=405,
	 *			description="Invalid method",
	 *			@OA\JsonContent(ref="#/components/schemas/Exception"),
	 *			@OA\XmlContent(ref="#/components/schemas/Exception"),
	 *		),
	 *),
	 * @OA\Schema(
	 *		schema="Products_RecordsList_ResponseBody",
	 *		title="Products - Response action record list",
	 *		description="Module action record list response body",
	 *		type="object",
	 *		@OA\Property(
	 *			property="status",
	 *			description="A numeric value of 0 or 1 that indicates whether the communication is valid. 1 - success , 0 - error",
	 *			enum={0, 1},
	 *			type="integer",
	 *		),
	 *		@OA\Property(
	 *			property="result",
	 *			description="List of records",
	 *			type="object",
	 *			@OA\Property(
	 *				property="headers",
	 *				description="Column names",
	 *				type="object",
	 *				@OA\AdditionalProperties,
	 *			),
	 *			@OA\Property(
	 *				property="records",
	 *				description="Records display details",
	 *				type="object",
	 *				@OA\AdditionalProperties(type="object", ref="#/components/schemas/Record_Display_Details"),
	 *			),
	 *			@OA\Property(
	 *				property="rawData",
	 *				description="Records raw details",
	 *				type="object",
	 *				@OA\AdditionalProperties(type="object", ref="#/components/schemas/Record_Raw_Details"),
	 *			),
	 * 			@OA\Property(property="count", type="string", example=54),
	 * 			@OA\Property(property="isMorePages", type="boolean", example=true),
	 * 		),
	 *	),
	 */

	/** {@inheritdoc}  */
	public function createQuery(): void
	{
		$this->isUserPermissions = \Api\Portal\Privilege::USER_PERMISSIONS === $this->userData['type'];
		if ($this->isUserPermissions) {
			parent::createQuery();
		} else {
			if ($parent = $this->getParentCrmId()) {
				$this->parentRecordModel = \Vtiger_Record_Model::getInstanceById($parent, 'Accounts');
				$pricebookId = $this->parentRecordModel->get('pricebook_id');
				if (empty($pricebookId)) {
					parent::createQuery();
				} else {
					parent::createQuery();
					$this->queryGenerator->setCustomColumn('vtiger_pricebookproductrel.listprice');
					$this->queryGenerator->addJoin([
						'LEFT JOIN',
						'vtiger_pricebookproductrel',
						"vtiger_pricebookproductrel.pricebookid={$pricebookId} AND vtiger_pricebookproductrel.productid = vtiger_products.productid"]
					);
				}
			} else {
				parent::createQuery();
			}
		}
		$storage = $this->getUserStorageId();
		if ($storage) {
			$this->queryGenerator->setCustomColumn('u_#__istorages_products.qtyinstock as storage_qtyinstock');
			$this->queryGenerator->addJoin([
				'LEFT JOIN',
				'u_#__istorages_products',
				"u_#__istorages_products.crmid={$storage} AND u_#__istorages_products.relcrmid = vtiger_products.productid"]
			);
		}
	}

	/** {@inheritdoc}  */
	protected function isRawData(): bool
	{
		return true;
	}

	/** {@inheritdoc}  */
	protected function getRecordFromRow(array $row): array
	{
		$record = parent::getRecordFromRow($row);
		$unitPrice = (new \Vtiger_MultiCurrency_UIType())->getValueForCurrency($row['unit_price'], \App\Fields\Currency::getDefault()['id']);
		$regionalTaxes = $availableTaxes = '';
		if ($this->isUserPermissions) {
			$availableTaxes = 'LBL_GROUP';
		} else {
			if (isset($this->parentRecordModel)) {
				$availableTaxes = $this->parentRecordModel->get('accounts_available_taxes');
				$regionalTaxes = $this->parentRecordModel->get('taxes');
			}
			if (!empty($row['listprice'])) {
				$unitPrice = $row['listprice'];
			}
		}
		$record['unit_price'] = \CurrencyField::convertToUserFormatSymbol($unitPrice);
		$taxParam = \Api\Portal\Record::getTaxParam($availableTaxes, $row['taxes'] ?? '', $regionalTaxes);
		$taxConfig = \Vtiger_Inventory_Model::getTaxesConfig();
		$record['unit_gross'] = \CurrencyField::convertToUserFormatSymbol($unitPrice + (new \Vtiger_Tax_InventoryField())->getTaxValue($taxParam, $unitPrice, (int) $taxConfig['aggregation']));
		return $record;
	}

	/** {@inheritdoc}  */
	protected function getRawDataFromRow(array $row): array
	{
		$row = parent::getRawDataFromRow($row);
		$unitPrice = $row['unit_price'] = (new \Vtiger_MultiCurrency_UIType())->getValueForCurrency($row['unit_price'], \App\Fields\Currency::getDefault()['id']);
		$regionalTaxes = $availableTaxes = '';
		if ($this->isUserPermissions) {
			$availableTaxes = 'LBL_GROUP';
		} else {
			if (isset($this->parentRecordModel)) {
				$availableTaxes = $this->parentRecordModel->get('accounts_available_taxes');
				$regionalTaxes = $this->parentRecordModel->get('taxes');
			}
			if (!empty($row['listprice'])) {
				$unitPrice = $row['unit_price'] = $row['listprice'];
			}
		}
		$taxParam = \Api\Portal\Record::getTaxParam($availableTaxes, $row['taxes'] ?? '', $regionalTaxes);
		$taxConfig = \Vtiger_Inventory_Model::getTaxesConfig();
		$row['unit_gross'] = $unitPrice + (new \Vtiger_Tax_InventoryField())->getTaxValue($taxParam, $unitPrice, (int) $taxConfig['aggregation']);
		$row['qtyinstock'] = $row['storage_qtyinstock'] ?? 0;
		return $row;
	}

	/** {@inheritdoc}  */
	protected function getColumnNames(): array
	{
		$headers = parent::getColumnNames();
		$headers['unit_gross'] = \App\Language::translate('LBL_GRAND_TOTAL');
		return $headers;
	}
}
