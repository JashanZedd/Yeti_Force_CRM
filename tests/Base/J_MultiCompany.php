<?php

/**
 * MultiCompany test file.
 *
 * @package   Tests
 *
 * @copyright YetiForce S.A.
 * @license   YetiForce Public License 5.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Sławomir Rembiesa <s.rembiesa@yetiforce.com>
 */

namespace Tests\Base;

use Exception;

/**
 * Class testing creating and setting roles and privileges for MultiCompany records.
 */
class J_MultiCompany extends \Tests\Base
{
	/**
	 * Temporary User record object
	 *
	 * @var \Vtiger_Record_Model
	 */
	protected static $user;

	/**
	 * Temporary MultiCompany record object.
	 *
	 * @var \Vtiger_Record_Model
	 */
	protected static $recordMultiCompany;

	/**
	 * Temporary Roles Settings record object.
	 *
	 * @var \Settings_Roles_Record_Model
	 */
	protected static $role;

	/**
	 * Creating User module for tests.
	 *
	 * @return \Vtiger_Record_Model
	 */
	public static function createUser(): \Vtiger_Record_Model
	{
		$recordModel = \Vtiger_Record_Model::getCleanInstance('Users');
		$recordModel->set('user_name', 'TestMultiCompany');
		$recordModel->set('email1', "s.rembiesa@yetiforce.com");
		$recordModel->set('first_name', 'Test');
		$recordModel->set('last_name', 'MultiCompany');
		$recordModel->set('user_password', 'Demo12345678T');
		$recordModel->set('confirm_password', 'Demo12345678T');
		$recordModel->set('roleid', self::$role->getId());
		$recordModel->set('is_admin', 'on');
		$recordModel->save();
		self::$user = $recordModel;

		return $recordModel;
	}

	/**
	 * Creating MultiCompany module record for tests.
	 *
	 * @return \Vtiger_Record_Model
	 */
	public static function createMultiCompanyRecord(): \Vtiger_Record_Model
	{
		$recordModel = \Vtiger_Record_Model::getCleanInstance('MultiCompany');
		$recordModel->set('company_name', 'TestMulti sp. z o.o.');
		$recordModel->set('companyid1', '23123214141412');
		$recordModel->set('email1', 'mail@testowy.pl');
		$recordModel->set('assigned_user_id', \App\User::getCurrentUserId());
		$recordModel->save();
		self::$recordMultiCompany = $recordModel;

		return $recordModel;
	}

	/**
	 * Creating new role for test.
	 *
	 * @return \Settings_Roles_Record_Model
	 */
	public static function createRole(): \Settings_Roles_Record_Model
	{
		$recordModel = new \Settings_Roles_Record_Model();
		$parentRoleId = 'H1';

		$parentRole = \Settings_Roles_Record_Model::getInstanceById($parentRoleId);
		$recordModel->set('changeowner', 1)
			->set('searchunpriv', null)
			->set('listrelatedrecord', 0)
			->set('previewrelatedrecord',0)
			->set('editrelatedrecord', 0)
			->set('permissionsrelatedfield', 0)
			->set('globalsearchadv', 1)
			->set('assignedmultiowner', 1)
			->set('clendarallorecords', 3)
			->set('company', 1)
			->set('auto_assign', 0)
			->set('allowassignedrecordsto', 1);

		$recordModel->set('rolename', 'TestMultiSelect');
		$parentRole->addChildRole($recordModel);

		self::$role = $recordModel;

		return $recordModel;
	}

	/**
	 * Testing creating MultiCompany record.
	 *
	 * @return void
	 */
	public function testCreateMultiCompanyRecords(): void
	{
		$multiCompany = self::createMultiCompanyRecord(true);
		$this->assertIsNumeric($multiCompany->getId());
		$this->assertInstanceOf(\Vtiger_Record_Model::class, $multiCompany);
	}

	/**
	 * Testing creating role.
	 *
	 * @return void
	 */
	public function testCreateRole(): void
	{
		$role = self::createRole();
		$this->assertIsString($role->getId());
		$this->assertInstanceOf(\Settings_Roles_Record_Model::class, $role);
	}

	/**
	 * Test creating user.
	 *
	 * @return void
	 */
	public function testCreateUser(): void
	{
		$user = self::createUser();
		$this->assertIsInt($user->getId());
		$this->assertInstanceOf(\Vtiger_Record_Model::class, $user);
	}

	/**
	 * Testing privilege to delete.
	 *
	 * @return void
	 */
	public function testPrivilegeToDelete(): void
	{
		$this->assertTrue(self::$recordMultiCompany->privilegeToDelete());
	}

	/**
	 * Testing not privilege to delete.
	 *
	 * @return void
	 */
	public function testNotPrivilegeToDelete(): void
	{
		self::$role->set('company', self::$recordMultiCompany->getId());
		self::$role->save();

		self::$recordMultiCompany->clearPrivilegesCache();

		$this->assertFalse(self::$recordMultiCompany->privilegeToDelete());
	}

	/**
	 * Testing Reloaded by MultiCompany by image loading
	 *
	 * @return void
	 */
	public function testReloadByMultiCompany(): void
	{
		$userModel = \App\User::getUserModel(self::$user->getId());
		$multiCompanyLogo = $userModel->get('multiCompanyLogo');

		$this->assertSame('logo_yetiforce.png', $multiCompanyLogo['name']);
		$this->assertSame('11111111111111111111111111111111111111111111111111', $multiCompanyLogo['key']);

		$filePath = ROOT_DIRECTORY . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'MultiImage' . DIRECTORY_SEPARATOR . '0.jpg';
		$recordModel = self::$recordMultiCompany;
		$fileObj = \App\Fields\File::loadFromPath($filePath);
		$hash = $fileObj->generateHash(true, $filePath);
		$attach[] = [
			'name' => '0.jpg',
			'size' => $fileObj->getSize(),
			'key' => $hash,
			'path' => $fileObj->getPath(),
			'type' => $fileObj->getMimeType()
		];

		$recordModel->set('logo', \App\Json::encode($attach));
		$recordModel->save();
		$userModel = \App\User::getUserModel(self::$user->getId());

		$fieldModel = $recordModel->getField('logo');
		$data = \App\Json::decode(\App\Purifier::decodeHtml($fieldModel->getUITypeModel()->getDisplayValueEncoded($recordModel->get('logo'), $recordModel->getId(), $fieldModel->getFieldInfo())));

		$this->assertSame('0.jpg', $data[0]['name']);
		$this->assertSame($fileObj->getSize(), $data[0]['size']);
		$this->assertSame($hash, $data[0]['key']);

		$this->assertNotSame($multiCompanyLogo['name'], $data[0]['name']);
		$this->assertNotSame($multiCompanyLogo['key'], $data[0]['key']);
	}

	/**
	 * Cleaning after tests
	 *
	 * @return void
	 */
	public static function tearDownAfterClass(): void
	{
		self::$recordMultiCompany->delete();
		self::$role->delete(\Settings_Roles_Record_Model::getInstanceById('H1'));
		\Users_Record_Model::deleteUserPermanently(self::$user->getId(), \Users_Record_Model::getCurrentUserModel()->getId());
	}
}
