<?php
/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ********************************************************************************** */

class Mobile_WS_FetchModuleOwners extends Mobile_WS_Controller
{

	function process(Mobile_API_Request $request)
	{
		$current_user = vglobal('current_user');

		$response = new Mobile_API_Response();
		$current_user = $this->getActiveUser();

		$currentUserModel = Users_Record_Model::getInstanceFromUserObject($current_user);

		$moduleName = $request->get('module');
		$users = $this->getUsers($currentUserModel, $moduleName);
		$groups = $this->getGroups($currentUserModel, $moduleName);

		$result = array('users' => $users, 'groups' => $groups);
		$response->setResult($result);

		return $response;
	}

	function getUsers($currentUserModel, $moduleName)
	{
		$users = \includes\fields\Owner::getInstance($moduleName, $currentUserModel)->getAccessibleUsersForModule();
		$userIds = array_keys($users);
		$usersList = array();
		$usersWSId = Mobile_WS_Utils::getEntityModuleWSId('Users');
		foreach ($userIds as $userId) {
			$userRecord = Users_Record_Model::getInstanceById($userId, 'Users');
			$usersList[] = array('value' => $usersWSId . 'x' . $userId,
				'label' => $userRecord->get('first_name') . ' ' . $userRecord->get('last_name')
			);
		}
		return $usersList;
	}

	function getGroups($currentUserModel, $moduleName)
	{
		$groups = \includes\fields\Owner::getInstance($moduleName, $currentUserModel)->getAccessibleGroupForModule();
		$groupIds = array_keys($groups);
		$groupsList = array();
		$groupsWSId = Mobile_WS_Utils::getEntityModuleWSId('Groups');
		foreach ($groupIds as $groupId) {
			$groupsList[] = array('value' => $groupsWSId . 'x' . $groupId,
				'label' => \includes\fields\Owner::getGroupName($groupId)
			);
		}
		return $groupsList;
	}
}
