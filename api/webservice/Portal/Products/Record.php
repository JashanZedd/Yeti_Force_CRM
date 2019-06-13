<?php
/**
 * The file contains: Get record detail class.
 *
 * @package Api
 *
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Arkadiusz Adach <a.adach@yetiforce.com>
 */

namespace Api\Portal\Products;

/**
 * Get record detail class.
 */
class Record extends \Api\Portal\BaseModule\Record
{
	/**
	 * {@inheritdoc}
	 */
	public function get(): array
	{
		$response = parent::get();
		$tax = (float) current($response['rawData']['taxes_info'])['value'] ?? 0.0;
		$unitPrice = \Api\Portal\Record::getPriceFromPricebook($this->getParentCrmId(), $this->controller->request->getInteger('record'));
		if (\Api\Portal\Privilege::USER_PERMISSIONS !== $this->getPermissionType()) {
			if (null === $unitPrice) {
				$unitPrice = (new \Vtiger_MultiCurrency_UIType())->getValueForCurrency($response['rawData']['unit_price'] ?? [], \App\Fields\Currency::getDefault()['id']);
			}
			$response['ext']['unit_price'] = $unitPrice;
			$response['ext']['unit_gross'] = $unitPrice + ($unitPrice * $tax / 100.00);
		}
		return $response;
	}
}
