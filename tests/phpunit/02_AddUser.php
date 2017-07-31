<?php

/**
 * AddUser test class
 * @package YetiForce.Test
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 2.0 (licenses/License.html or yetiforce.com)
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
use PHPUnit\Framework\TestCase;

class AddUser extends TestCase
{

	/**
	 * Testing user creation
	 */
	public function test()
	{
		$user = Vtiger_Record_Model::getCleanInstance('Users');
		$user->set('user_name', 'demo');
		$user->set('email1', 'demo@yetiforce.com');
		$user->set('first_name', 'Demo');
		$user->set('last_name', 'YetiForce');
		$user->set('user_password', 'demo');
		$user->set('confirm_password', 'demo');
		$user->set('roleid', 'H2');
		$user->save();
		define('TESTS_USER_ID', $user->getId());
		$userForus = CRMEntity::getInstance('Users');
		$currentUser = $userForus->retrieveCurrentUserInfoFromFile(TESTS_USER_ID);
		vglobal('current_user', $currentUser);
		App\User::setCurrentUserId(TESTS_USER_ID);
		$this->assertInternalType('int', TESTS_USER_ID);
	}
}
