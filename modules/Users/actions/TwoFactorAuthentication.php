<?php

/**
 * Two factor authentication action class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Adach <a.adach@yetiforce.com>
 */
class Users_TwoFactorAuthentication_Action extends \App\Controller\Action
{
	use \App\Controller\ExposeMethod;

	/**
	 * Users_TwoFactorAuthentication_Action constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('secert');
		$this->exposeMethod('off');
	}

	/**
	 * {@inheritdoc}
	 */
	public function checkPermission(\App\Request $request)
	{
		$mode = $request->getMode();
		if (AppConfig::security('USER_AUTHY_MODE') === 'TOTP_OFF') {
			throw new \App\Exceptions\NoPermittedToRecord('ERR_NO_PERMISSIONS_FOR_THE_RECORD', 406);
		}
		if ($mode==='off' && AppConfig::security('USER_AUTHY_MODE') !== 'TOTP_OPTIONAL') {
			throw new \App\Exceptions\NoPermittedToRecord('ERR_NO_PERMISSIONS_FOR_THE_RECORD', 406);
		}
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function process(\App\Request $request)
	{
		$mode = $request->getMode();
		if (!empty($mode) && $this->isMethodExposed($mode)) {
			return $this->$mode($request);
		}
		$this->secret($request);
	}

	/**
	 * Setting the secret code.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\IllegalValue
	 */
	public function secret(\App\Request $request)
	{
		$moduleName = $request->getModule();
		$secret = $request->getByType('secret', 'Alnum');
		$userCode = $request->getInteger('user_code');
		$checkResult = Users_Totp_Authmethod::verifyCode($secret, $userCode);
		if ($checkResult) {
			$userRecordModel = Users_Record_Model::getInstanceById(\App\User::getCurrentUserRealId(), $moduleName);
			$userRecordModel->set('authy_secret_totp', $secret);
			$userRecordModel->set('authy_methods', 'PLL_AUTHY_TOTP');
			$userRecordModel->save();
		}
		$response = new Vtiger_Response();
		$response->setResult([
			'message'=> \App\Language::translate('LBL_AUTHY_SECRET_TOTP_SUCCESS', 'Users'),
			'success' => $checkResult
		]);
		$response->emit();
	}

	/**
	 * Turning off the 2FA.
	 *
	 * @param \App\Request $request
	 */
	public function off(\App\Request $request)
	{
		$moduleName = $request->getModule();
		$secret = \App\User::getUserModel(\App\User::getCurrentUserRealId())->getDetail('authy_secret_totp');
		$userCode = $request->getInteger('user_code');
		$checkResult = Users_Totp_Authmethod::verifyCode($secret, $userCode);
		if ($checkResult) {
			$userRecordModel = Users_Record_Model::getInstanceById(\App\User::getCurrentUserRealId(), $moduleName);
			$userRecordModel->set('authy_secret_totp', '');
			$userRecordModel->set('authy_methods', '');
			$userRecordModel->save();
		}
		$response = new Vtiger_Response();
		$response->setResult([
			'message' => \App\Language::translate('LBL_AUTHY_SECRET_TOTP_SUCCESS', 'Users'),
			'success' => $checkResult
		]);
		$response->emit();
	}
}
