<?php

/**
 * YetiForce status action class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Sławomir Kłos <s.klos@yetiforce.com>
 */
class Settings_YetiForce_Status_Action extends Settings_Vtiger_Save_Action
{
	/**
	 * Process user request.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\NoPermitted
	 */
	public function process(\App\Request $request)
	{
		if (!$request->has('flagName')) {
			throw new \App\Exceptions\NoPermitted('LBL_PERMISSION_DENIED');
		}
		$flagName = $request->getByType('flagName', 2);
		$response = new Vtiger_Response();
		$config = new \App\Configurator('yetiforce');
		if (isset(\App\YetiForce\Status::$variables[$flagName])) {
			switch (\App\YetiForce\Status::$variables[$flagName]) {
				case 'bool':
					$config->set($flagName, $request->getByType('newParam', 'Digital') === '1');
					$response->setResult([
						'success' => true,
						'message' => App\Language::translate('LBL_SAVED', $request->getModule(false)),
					]);
					break;
				default:
					$config->set($flagName, $request->getByType('newParam', 'Text'));
					$response->setResult([
						'success' => true,
						'message' => App\Language::translate('LBL_SAVED', $request->getModule(false)),
					]);
					break;
			}
		}
		$config->save();
		$response->emit();
	}
}
