<?php
/* +***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 * *********************************************************************************************************************************** */

class OSSMail_index_View extends Vtiger_Index_View
{

	public function process(Vtiger_Request $request)
	{
		$moduleName = $request->getModule();
		$url = OSSMail_Record_Model::GetSite_URL() . 'modules/OSSMail/roundcube/';
		$config = Settings_Mail_Config_Model::getConfig('autologin');
		if ($config['autologinActive'] == 'true') {
			$account = OSSMail_Autologin_Model::getAutologinUsers();
			if ($account) {
				$rcUser = (isset($_SESSION['AutoLoginUser']) && array_key_exists($_SESSION['AutoLoginUser'], $account)) ? $account[$_SESSION['AutoLoginUser']] : reset($account);
				require_once 'modules/OSSMail/RoundcubeLogin.class.php';
				$rcl = new RoundcubeLogin($url, false);
				//$rcl->setHostname('fdc.org.pl');
				//$rcl->setPort(143);
				//$rcl->setSSL(false);
				try {
					if ($rcl->isLoggedIn()) {
						if ($rcl->getUsername() != $rcUser['username']) {
							$rcl->logout();
							$rcl->login($rcUser['username'], $rcUser['password']);
						}
					} else {
						$rcl->login($rcUser['username'], $rcUser['password']);
					}
				} catch (RoundcubeLoginException $ex) {
					$log = vglobal('log');
					$log->error('OSSMail_index_View|RoundcubeLoginException: ' . $ex->getMessage());
				}
			}
		}
		$viewer = $this->getViewer($request);
		$viewer->assign('URL', $url);
		$viewer->view('index.tpl', $moduleName);
	}
}
