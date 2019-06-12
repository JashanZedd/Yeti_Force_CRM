<?php
/**
 * The file contains: PaymentsIn handler class.
 *
 * @package Handler
 *
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Arkadiusz Adach <a.adach@yetiforce.com>
 */
class PaymentsIn_PaymentsInHandler_Handler
{
	/**
	 * EntityAfterSave handler function.
	 *
	 * @param App\EventHandler $eventHandler
	 *
	 * @return void
	 */
	public function entityAfterSave(App\EventHandler $eventHandler)
	{
		$recordModel = $eventHandler->getRecordModel();
		if ($this->canUpdatePaymentStatus($recordModel)) {
			(new \App\BatchMethod(['method' => 'PaymentsIn_Module_Model::updatePaymentStatus', 'params' => [$recordModel->get('ssingleordersid')]]))->save();
		}
	}

	/**
	 * Checking if you can update the payment status.
	 *
	 * @param Vtiger_Record_Model $recordModel
	 *
	 * @return bool
	 */
	private function canUpdatePaymentStatus(Vtiger_Record_Model $recordModel): bool
	{
		if ($recordModel->isEmpty('ssingleordersid')) {
			return false;
		}
		$ordersId = (int) $recordModel->get('ssingleordersid');
		if ((int) $recordModel->get('currency_id') !== \App\Record::getCurrencyIdFromInventory($ordersId, 'SSingleOrders')) {
			\App\Log::warning("The payment is in a different currency than the order. SSingleOrdersId: {$ordersId}");
			return false;
		}
		return $recordModel->isNew() || false !== $recordModel->getPreviousValue('paymentsin_status');
	}
}
