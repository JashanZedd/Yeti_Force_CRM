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

class Settings_SupportProcesses_Module_Model extends Settings_Vtiger_Module_Model
{

	/**
	 * Gets ticket status for support processes
	 * @return - array of ticket status
	 */
	public static function getTicketStatus()
	{
		$log = vglobal('log');
		$adb = PearDatabase::getInstance();
		$log->debug("Entering Settings_SupportProcesses_Module_Model::getTicketStatus() method ...");
		$sql = 'SELECT * FROM `vtiger_ticketstatus`;';
		$result = $adb->query($sql);
		$rowsNum = $adb->num_rows($result);

		for ($i = 0; $i < $rowsNum; $i++) {
			$return[$i]['id'] = $adb->query_result($result, $i, 'ticketstatus_id');
			$return[$i]['statusTranslate'] = vtranslate($adb->query_result($result, $i, 'ticketstatus'), 'HelpDesk');
			$return[$i]['status'] = $adb->query_result($result, $i, 'ticketstatus');
		}
		$log->debug("Exiting Settings_SupportProcesses_Module_Model::getTicketStatus() method ...");
		return $return;
	}

	/**
	 * Gets ticket status for support processes from support_processes
	 * @return - array of ticket status
	 */
	public static function getTicketStatusNotModify()
	{
		$log = vglobal('log');
		$adb = PearDatabase::getInstance();
		$log->debug("Entering Settings_SupportProcesses_Module_Model::getTicketStatusNotModify() method ...");
		$sql = 'SELECT ticket_status_indicate_closing FROM `vtiger_support_processes`;';
		$result = $adb->query($sql);

		$ticketStatus = $adb->query_result($result, 0, 'ticket_status_indicate_closing');
		if ($ticketStatus == '')
			$return = [];
		else {
			$return = explode(",", $ticketStatus);
		}

		$log->debug("Exiting Settings_SupportProcesses_Module_Model::getTicketStatusNotModify() method ...");
		return $return;
	}

	/**
	 * Update ticket status for support processes from support_processes
	 * @return - array of ticket status
	 */
	public function updateTicketStatusNotModify($data)
	{
		$log = vglobal('log');
		$adb = PearDatabase::getInstance();
		$log->debug("Entering Settings_SupportProcesses_Module_Model::updateTicketStatusNotModify() method ...");
		$deleteQuery = "UPDATE `vtiger_support_processes` SET `ticket_status_indicate_closing` = NULL WHERE `id` = 1";
		$adb->query($deleteQuery);
		if ('null' != $data) {
			$insertQuery = "UPDATE `vtiger_support_processes` SET `ticket_status_indicate_closing` = ? WHERE `id` = 1";
			$data = implode(',', $data);
			$adb->pquery($insertQuery, array($data));
		}
		$log->debug("Exiting Settings_SupportProcesses_Module_Model::updateTicketStatusNotModify() method ...");
		return TRUE;
	}

	public function getAllTicketStatus()
	{
		$adb = PearDatabase::getInstance();
		$log = vglobal('log');
		$log->debug("Entering Settings_SupportProcesses_Module_Model::getAllTicketStatus() method ...");
		$sql = 'SELECT `ticketstatus` FROM `vtiger_ticketstatus`';
		$result = $adb->query($sql);
		$rowsNum = $adb->num_rows($result);
		for ($i = 0; $i < $rowsNum; $i++) {
			$ticketStatus[] = $adb->query_result($result, $i, 'ticketstatus');
		}
		return $ticketStatus;
	}

	public static function getOpenTicketStatus()
	{
		$log = vglobal('log');
		$getTicketStatusClosed = self::getTicketStatusNotModify();
		$log->debug("Entering Settings_SupportProcesses_Module_Model::getOpenTicketStatus() method ...");
		if (empty($getTicketStatusClosed)) {
			$result = FALSE;
		} else {
			$getAllTicketStatus = self::getAllTicketStatus();
			foreach ($getTicketStatusClosed as $key => $closedStatus) {
				foreach ($getAllTicketStatus as $key => $status) {
					if ($closedStatus == $status)
						unset($getAllTicketStatus[$key]);
				}
			}
			$result = $getAllTicketStatus;
		}
		return $result;
	}
}
