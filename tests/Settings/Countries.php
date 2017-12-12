<?php
/**
 * Countries test class
 * @package YetiForce.Test
 * @copyright YetiForce Sp. z o.o.
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Wojciech Bruggemann <w.bruggemann@yetiforce.com>
 */
namespace Tests\Settings;

class Countries extends \Tests\Base
{

	/**
	 * Testing update all statuses
	 * @param int $status
	 * @dataProvider providerForUpdateAllStatuses
	 */
	public function testUpdateAllStatuses($status)
	{
		$moduleModel = new \Settings_Countries_Module_Model();
		$moduleModel->updateAllStatuses($status);
		$exists = (new \App\Db\Query())->from('u_#__countries')->where(['status' => (int) !$status])->exists();
		$this->assertFalse($exists, 'Exists at least one record with wrong value of status');
	}

	/**
	 * Data provider for testUpdateAllStatuses
	 * @return array
	 * @codeCoverageIgnore
	 */
	public function providerForUpdateAllStatuses()
	{
		return [
			[1],
			[0]
		];
	}

	/**
	 * Testing update sequence
	 */
	public function testUpdateSequence()
	{
		$moduleModel = new \Settings_Countries_Module_Model();
		$rows = $this->getAllRows();
		$keys = [];
		$values = [];
		foreach ($rows as $row) {
			$keys[] = $row['sortorderid'];
			$values[] = $row['id'];
		}
		$originKeys = $keys;
		shuffle($keys);
		$sequence = array_combine($keys, $values);
		$moduleModel->updateSequence($sequence);
		$rows2 = $this->getAllRows();
		$this->assertTrue($rows !== $rows2, 'After update sequence the data is not changed');

		$sequence = array_combine($originKeys, $values);
		$moduleModel->updateSequence($sequence);
		$rows3 = $this->getAllRows();
		$this->assertTrue($rows === $rows3, 'After update original sequence the data is different than original data');
	}

	/**
	 * Testing update status
	 */
	public function testUpdateStatus()
	{
		$moduleModel = new \Settings_Countries_Module_Model();
		$row = (new \App\Db\Query())->from('u_#__countries')->one();
		$id = $row['id'];
		$status = $row['status'] ? 0 : 1;
		$result = $moduleModel->updateStatus($id, $status);
		$this->assertGreaterThan(0, $result, 'There is none any results after update');
		$status2 = $this->getValueOfField($id, 'status');
		$this->assertEquals($status, $status2, 'There is none any changes after update');
	}

	/**
	 * Testing update phone
	 */
	public function testUpdatePhone()
	{
		$moduleModel = new \Settings_Countries_Module_Model();
		$row = (new \App\Db\Query())->from('u_#__countries')->one();
		$id = $row['id'];
		$phone = $row['phone'] ? 0 : 1;
		$result = $moduleModel->updatePhone($id, $phone);
		$this->assertGreaterThan(0, $result, 'There is none any results after update');
		$status2 = $this->getValueOfField($id, 'phone');
		$this->assertEquals($phone, $status2, 'There is none any changes after update');
	}

	/**
	 * Testing update uitype
	 */
	public function testUpdateUitype()
	{
		$moduleModel = new \Settings_Countries_Module_Model();
		$row = (new \App\Db\Query())->from('u_#__countries')->one();
		$id = $row['id'];
		$uitype = $row['uitype'] ? 0 : 1;
		$result = $moduleModel->updateUitype($id, $uitype);
		$this->assertGreaterThan(0, $result, 'There is none any results after update');
		$status2 = $this->getValueOfField($id, 'uitype');
		$this->assertEquals($uitype, $status2, 'There is none any changes after update');
	}

	/**
	 * Testing get all records
	 */
	public function testGetAll()
	{
		$allRecords = \Settings_Countries_Record_Model::getAll();
		$count = (new \App\Db\Query())->from('u_#__countries')->count();
		$this->assertCount($count, $allRecords, 'Count of all record is different than should be');
	}

	/**
	 * Get value of field as scalar
	 * @param int $id
	 * @param string $fieldName
	 * @return string
	 */
	private function getValueOfField($id, $fieldName)
	{
		return (new \App\Db\Query())->from('u_#__countries')->select($fieldName)->where(['id' => $id])->scalar();
	}

	/**
	 * Get all rows
	 * @return array
	 */
	private function getAllRows()
	{
		return (new \App\Db\Query())->from('u_#__countries')->all();
	}
}
