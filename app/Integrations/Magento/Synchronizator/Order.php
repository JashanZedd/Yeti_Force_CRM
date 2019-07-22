<?php

/**
 * Synchronize orders.
 *
 * @package Integration
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Dudek <a.dudek@yetiforce.com>
 */

namespace App\Integrations\Magento\Synchronizator;

/**
 * Category class.
 */
class Order extends Integrators\Order
{
	/**
	 * {@inheritdoc}
	 */
	public function process()
	{
		$this->getMapping('order');
		$this->getMapping('product');
		$this->config = \App\Integrations\Magento\Config::getInstance();
		$this->lastScan = $this->config::getLastScan('order');
		if (!$this->lastScan['start_date'] || (0 === (int) $this->lastScan['id'] && $this->lastScan['start_date'] === $this->lastScan['end_date'])) {
			$this->config::setScan('order');
			$this->lastScan = $this->config::getLastScan('order');
		}
		if ($this->checkOrders()) {
			$this->config::setEndScan('order', $this->lastScan['start_date']);
		}
	}

	/**
	 * Check orders.
	 *
	 * @throws \App\Exceptions\AppException
	 * @throws \ReflectionException
	 * @throws \yii\db\Exception
	 *
	 * @return bool
	 */
	public function checkOrders(): bool
	{
		$allChecked = true;
		$orders = $this->getOrders();
		if (!empty($orders)) {
			foreach ($orders as $id => $order) {
				if (!isset($this->mapCrm['order'][$id])) {
					$this->saveOrderCrm($order);
				}
				$this->config::setScan('order', 'id', $id);
			}
			$allChecked = false;
		}
		return $allChecked;
	}

	/**
	 * Save order in YetiForce.
	 *
	 * @param array $data
	 *
	 * @return mixed|int
	 */
	public function saveOrderCrm(array $data)
	{
		$orderFields = new \App\Integrations\Magento\Synchronizator\Maps\Order();
		$orderFields->setData($data);
		$dataCrm = $orderFields->getDataCrm();
		$value = 0;
		if (!empty($dataCrm)) {
			try {
				$recordModel = \Vtiger_Record_Model::getCleanInstance('SSingleOrders');
				$recordModel->setData($dataCrm);
				if ($this->saveInventoryCrm($recordModel, $data['items'], $data['extension_attributes']['shipping_assignments'], $orderFields)) {
					$recordModel->save();
					$this->saveMapping($data['entity_id'], $recordModel->getId(), 'order');
				}
				$value = $recordModel->getId();
			} catch (\Throwable $ex) {
				\App\Log::error('Error during saving YetiForce order: ' . $ex->getMessage(), 'Integrations/Magento');
			}
		}
		return $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSearchCriteria($ids, int $pageSize = 10): string
	{
		$searchCriteria[] = parent::getSearchCriteria($ids, $pageSize);
		$searchCriteria[] = 'searchCriteria[filter_groups][3][filters][0][value]=' . \App\Config::component('Magento', 'storeId');
		$searchCriteria[] = 'searchCriteria[filter_groups][3][filters][0][field]=store_id';
		$searchCriteria[] = 'searchCriteria[filter_groups][3][filters][0][conditionType]=eq';
		return implode('&', $searchCriteria);
	}
}
