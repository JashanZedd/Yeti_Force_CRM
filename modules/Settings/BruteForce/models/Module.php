<?php
/*+***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 *************************************************************************************************************************************/
class Settings_BruteForce_Module_Model extends Settings_Vtiger_Module_Model {
	public function getConfig() {
		$db = PearDatabase::getInstance();
		$result = $db->query("SELECT * FROM vtiger_bruteforce", true);
		for($i = 0; $i < $db->num_rows($result); $i++){
			$output[] = $db->query_result($result, $i, 'value');
		}
		return $output;
	}

	static public function getBruteForceSettings() {
		$db = PearDatabase::getInstance();
		$result = $db->query("SELECT * FROM vtiger_bruteforce", true);
		$output = $db->query_result_rowdata($result, 0);
		return $output;
	}    

	static public function getBlockedIP() {		
		$db = PearDatabase::getInstance();
		$bruteforceSettings = self::getBruteForceSettings();
		$attempsNumber = $bruteforceSettings[0];
		$blockTime = $bruteforceSettings[1];
		$now = date("Y-m-d H:i:s");

		$query = "SELECT  COUNT(*) AS COUNT, user_ip, GROUP_CONCAT(DISTINCT(user_name)), login_time, GROUP_CONCAT(DISTINCT(browser))"
			 . " FROM `vtiger_loginhistory` vlh WHERE "
			 . "STATUS = 'Failed login' && "
			 . "(UNIX_TIMESTAMP(vlh.login_time) - UNIX_TIMESTAMP(ADDDATE(?, INTERVAL -$blockTime MINUTE))) > 0 "
			 . "GROUP BY user_ip "
			 . "HAVING COUNT>?";
		$result = $db->pquery($query, array($now, $attempsNumber));       

		for ($i=0; $i < $db->num_rows($result); $i++) {
			$output[$i]['ip'] = $db->query_result_raw($result, $i, 1);
			$output[$i]['users'] = $db->query_result_raw($result, $i, 2);
			$output[$i]['date'] = $db->query_result_raw($result, $i, 3);
			$output[$i]['browsers'] = $db->query_result_raw($result, $i, 4);
		}
		return $output;
	}

	static public function browserDetect() {
	   
		$browser = $_SERVER['HTTP_USER_AGENT'];
	   
		if(strpos($browser, 'MSIE') !== FALSE)
		   return 'Internet explorer';
		 elseif(strpos($browser, 'Trident') !== FALSE) //For Supporting IE 11
			return 'Internet explorer';
		 elseif(strpos($browser, 'Firefox') !== FALSE)
		   return 'Mozilla Firefox';
		 elseif(strpos($browser, 'Chrome') !== FALSE)
		   return 'Google Chrome';
		 elseif(strpos($browser, 'Opera Mini') !== FALSE)
		   return "Opera Mini";
		 elseif(strpos($browser, 'Opera') !== FALSE)
		   return "Opera";
		 elseif(strpos($browser, 'Safari') !== FALSE)
		   return "Safari";
		 else
		   return 'unknow';       
	}

	static public function checkBlocked() {
		$db = PearDatabase::getInstance();

		$query = "SELECT * FROM `vtiger_bruteforce` LIMIT 1";    
		$result = $db->pquery($query, array());
		$ip = $_SERVER['REMOTE_ADDR'];
		$now = date("Y-m-d H:i:s");

		$bruteforceSettings =  $db->query_result_rowdata($result, 0);
		$attempsNumber = $bruteforceSettings[0];
		$blockTime = $bruteforceSettings[1];
		  
		$query = "SELECT count(login_id) as cn FROM `vtiger_loginhistory` vlh WHERE STATUS = 'Failed login' && user_ip = ? && unblock = 0 && (UNIX_TIMESTAMP(vlh.login_time) - UNIX_TIMESTAMP(ADDDATE(?, INTERVAL -$blockTime MINUTE))) > 0";
		$result = $db->pquery($query, array ($ip, $now) );

		if($db->query_result_raw($result, 0, 'cn') >= $attempsNumber){
			return true;
		}
		return false;
	}

	public static function getAdminUsers(){
		global $adb;
		$query = "SELECT id, user_name FROM `vtiger_users` WHERE is_admin = 'on' AND deleted = 0";
		$result = $adb->query($query);
		$numRows = $adb->num_rows($result);
		for ($i=0; $i < $numRows; $i++) {
			$userId = $adb->query_result_raw($result, $i, 'id');
			$userName = $adb->query_result_raw($result, $i, 'user_name');
			$output[$userId] = $userName;
		}
	
		return $output;
	}

	public static function updateConfig($number, $timelock, $active){
		global $adb;
		
		if('true' == $active){
			$active = TRUE;
		}else{
			$active = FALSE;
		}
		
		$query = "UPDATE vtiger_bruteforce SET attempsnumber = ?, timelock = ?, active = ?;";
		$params = array($number, $timelock, $active);
		$result = $adb->pquery($query, $params);	

		return $result;
	}

	public static function updateUsersForNotifications($selectedUsers){
		global $adb;
		$deleteQuery = "DELETE FROM `vtiger_bruteforce_users`";
		$adb->query($deleteQuery);
		$insertQuery = "INSERT INTO `vtiger_bruteforce_users` (id) VALUES(?)";
		foreach ($selectedUsers as $userId) {
			$adb->pquery($insertQuery, array($userId));
		}
		
		return TRUE;
	}

	public static function getUsersForNotifications(){
		global $adb;
		$result = $adb->query("SELECT * FROM vtiger_bruteforce_users", true);
		$numRows = $adb->num_rows($result);
		for($i = 0; $i < $numRows; $i++){
			$id = $adb->query_result($result, $i, 'id');
			$output[$id] = $id;
		}
				
		return $output;
	}

	public static function sendNotificationEmail(){
		$usersId = self::getUsersForNotifications();
		foreach ($usersId as $id) {		
			$recordModel = Vtiger_Record_Model::getInstanceById($id, 'Users'); 
			$userEmail = $recordModel->get('email1'); 
			$emails[] = $userEmail;
		}
		$emailsList = implode(',', $emails);
		$data = array(
				'id' => 107,
				'to_email' => $emailsList,
				'module' => 'Contacts',
		);
		$recordModel = Vtiger_Record_Model::getCleanInstance('OSSMailTemplates');
		$mail_status = $recordModel->sendMailFromTemplate($data);
		 
		if($mail_status != 1) {
			throw new Exception('Error occurred while sending mail');
		} 
	}	
}