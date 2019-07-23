<?php
/**
 * Checking Close Validation Action Class.
 *
 * @package   Action
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Adrian Kon <a.kon@yetiforce.com>
 */
class HelpDesk_CheckValidateToClose_Action extends \App\Controller\Action
{
	/**
	 * {@inheritdoc}
	 */
	public function checkPermission(App\Request $request)
	{
		if (!\App\Privilege::isPermitted($request->getModule(), 'EditView', $request->getInteger('record'))) {
			throw new \App\Exceptions\NoPermitted('LBL_PERMISSION_DENIED', 406);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function process(App\Request $request)
	{
		if ($request->has('record')) {
			$recordModel = Vtiger_Record_Model::getInstanceById($request->getInteger('record'), $request->getModule());
			$result = $recordModel->checkValidateToClose($request->getByType('status', 'Text'));
		}else{
			$result = ['hasTimeControl' => ['result' => true], 'relatedTicketsClosed' => ['result' => true]];
		}

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}
}
