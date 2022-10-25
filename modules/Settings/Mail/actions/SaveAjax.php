<?php

/**
 * Settings mail SaveAjax action class.
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 */
class Settings_Mail_SaveAjax_Action extends Settings_Vtiger_Save_Action
{
	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('updateUsers');
		$this->exposeMethod('updateConfig');
		$this->exposeMethod('updateSignature');
		$this->exposeMethod('acceptanceRecord');
	}

	/**
	 * Process.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \ReflectionException
	 */
	public function process(App\Request $request)
	{
		$type = $request->getByType('type', \App\Purifier::STANDARD);
		try {
			$configModel = Settings_Mail_Config_Model::getInstance($type);
			foreach ($configModel->getFields($type) as $fieldName => $fieldModel) {
				if ($request->has($fieldName)) {
					$purifyType = $fieldModel->get('purifyType');
					$value = \is_array($purifyType) ? $request->getArray($fieldName, current($purifyType)) : $request->getByType($fieldName, $purifyType);
					$fieldUITypeModel = $fieldModel->getUITypeModel();
					$fieldUITypeModel->validate($value, true);
					$value = $fieldModel->getDBValue($value);
					$configModel->set($fieldName, $value);
				}
			}
			$configModel->save();
			\Settings_Vtiger_Tracker_Model::addDetail($configModel->getPreviousValue(), array_intersect_key($configModel->getData(), $configModel->getPreviousValue()));
			$result = ['notify' => ['type' => 'success', 'text' => \App\Language::translate('LBL_CHANGES_SAVED')]];
		} catch (\App\Exceptions\AppException $e) {
			$result = ['success' => false, 'message' => $e->getDisplayMessage()];
		}

		$response = new Vtiger_Response();
		$response->setResult($result);
		$response->emit();
	}

	/**
	 * Action to set users.
	 *
	 * @param \App\Request $request
	 */
	public function updateUsers(App\Request $request)
	{
		$id = $request->getInteger('id');
		$user = $request->getArray('user', 'Integer');
		Settings_Mail_Autologin_Model::updateUsersAutologin($id, $user);
		$response = new Vtiger_Response();
		$response->setResult([
			'success' => true,
			'message' => App\Language::translate('LBL_SAVED_CHANGES', $request->getModule(false)),
		]);
		$response->emit();
	}

	/**
	 * Action to set configuration.
	 *
	 * @param \App\Request $request
	 */
	public function updateConfig(App\Request $request)
	{
		$name = $request->getByType('name');
		$val = $request->getByType('val', 'Alnum');
		$type = $request->getByType('type');
		Settings_Mail_Config_Model::updateConfig($name, $val, $type);
		$response = new Vtiger_Response();
		$response->setResult([
			'success' => true,
			'message' => App\Language::translate('LBL_SAVED_CHANGES', $request->getModule(false)),
		]);
		$response->emit();
	}

	/**
	 * Action to update signature.
	 *
	 * @param \App\Request $request
	 */
	public function updateSignature(App\Request $request)
	{
		$val = $request->getForHtml('val');
		Settings_Mail_Config_Model::updateConfig('signature', $val, 'signature');
		$response = new Vtiger_Response();
		$response->setResult([
			'success' => true,
			'message' => App\Language::translate('LBL_SAVED_SIGNATURE', $request->getModule(false)),
		]);
		$response->emit();
	}

	/**
	 * Action to accept mail.
	 *
	 * @param \App\Request $request
	 */
	public function acceptanceRecord(App\Request $request)
	{
		Settings_Mail_Config_Model::acceptanceRecord($request->getInteger('id'));
		$response = new Vtiger_Response();
		$response->setResult([
			'success' => true,
			'message' => \App\Language::translate('LBL_RECORD_ACCEPTED', $request->getModule(false)),
		]);
		$response->emit();
	}
}
