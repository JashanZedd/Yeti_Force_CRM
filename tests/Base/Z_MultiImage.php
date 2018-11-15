<?php

/**
 * MultiImage test class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Sławomir Kłos <s.klos@yetiforce.com>
 */

namespace Tests\Base;

class Z_MultiImage extends \Tests\Base
{
	/**
	 * Files array.
	 *
	 * @var string[]
	 */
	public static $files = ['0.jpg', '1.png', '2.png', '3.jpg'];

	/**
	 * @codeCoverageIgnore
	 * Setting of tests.
	 */
	public static function setUpBeforeClass()
	{
		\mkdir('tests' . \DIRECTORY_SEPARATOR . 'tmp' . \DIRECTORY_SEPARATOR . 'MultiImage');
	}

	/**
	 * Data provider for the attach image to record test.
	 *
	 * @return []
	 * @codeCoverageIgnore
	 */
	public function providerImageForRecord()
	{
		$data = [];
		foreach (static::$files as $i => $name) {
			$data[] = ['Users', \App\User::getUserIdByName('admin'), 'imagename', $i];
			$data[] = ['Contacts', \Tests\Base\C_RecordActions::createContactRecord()->getId(), 'imagename', $i];
			$data[] = ['Products', \Tests\Base\C_RecordActions::createProductRecord()->getId(), 'imagename', $i];
		}
		return $data;
	}

	/**
	 * Data provider for the delete record images test.
	 *
	 * @return []
	 * @codeCoverageIgnore
	 */
	public function providerDeleteImageForRecord()
	{
		return [
			['Users', \App\User::getUserIdByName('admin'), 'imagename', 9],
			['Contacts', \Tests\Base\C_RecordActions::createContactRecord()->getId(), 'imagename', 10],
			['Products', \Tests\Base\C_RecordActions::createProductRecord()->getId(), 'imagename', 11],
		];
	}

	/**
	 * Attach image to record test.
	 *
	 * @param $module
	 * @param $record
	 * @param $field
	 * @param $file
	 *
	 * @throws \App\Exceptions\AppException
	 * @dataProvider providerImageForRecord
	 */
	public function testAttachImageToRecord($module, $record, $field, $file)
	{
		$filePathSrc = 'tests' . \DIRECTORY_SEPARATOR . 'data' . \DIRECTORY_SEPARATOR . 'MultiImage' . \DIRECTORY_SEPARATOR . static::$files[$file];
		$filePathDst = 'tests' . \DIRECTORY_SEPARATOR . 'tmp' . \DIRECTORY_SEPARATOR . 'MultiImage' . \DIRECTORY_SEPARATOR . md5(rand(0, 9999)) . substr(static::$files[$file], \strpos(static::$files[$file], '.'));
		\copy($filePathSrc, $filePathDst);
		$fileObj = \App\Fields\File::loadFromPath($filePathDst);
		$hash = $fileObj->generateHash(true, $filePathDst);
		$attach[] = [
			'name' => static::$files[$file],
			'size' => \vtlib\Functions::showBytes($fileObj->getSize()),
			'key' => $hash,
			'path' => $fileObj->getPath()
		];
		$recordModel = \Vtiger_Record_Model::getInstanceById($record, $module);
		$this->assertSame($record, $recordModel->getId(), 'Record ' . $record . '(' . $module . ') load error');
		$recordModel->set($field, \App\Json::encode($attach));
		$recordModel->save();
		$recordModel = \Vtiger_Record_Model::getInstanceById($record, $module);
		$fieldModel = $recordModel->getField($field);
		$data = \App\Json::decode(\App\Purifier::decodeHtml($fieldModel->getUITypeModel()->getDisplayValueEncoded($recordModel->get($field), $recordModel->getId(), $fieldModel->getFieldInfo()['limit'])));
		$this->assertNotEmpty($data);
		$this->assertSame(static::$files[$file], $data[0]['name'], 'File name should be equal');
		$this->assertSame(\vtlib\Functions::showBytes($fileObj->getSize()), $data[0]['size'], 'File size should be equal');
		$this->assertFileExists($fileObj->getPath(), 'File should exists');
		$this->assertSame($hash, $data[0]['key'], 'Key should be equal');
		parse_str(\parse_url($data[0]['imageSrc'])['query'], $url);
		$this->assertSame($module, $url['module'], 'Module in image url should be equal to provided');
		$this->assertSame($field, $url['field'], 'Field name in image url should be equal to provided');
		$this->assertSame($hash, $url['key'], 'Key in image url should be equal to provided');
		$this->assertSame((string) $record, $url['record'], 'Record in image url should be equal to provided');
	}

	/**
	 * Delete record image test.
	 *
	 * @param $module
	 * @param $record
	 * @param $field
	 * @param $file
	 *
	 * @throws \App\Exceptions\AppException
	 * @dataProvider providerDeleteImageForRecord
	 */
	public function testDeleteImage($module, $record, $field, $file)
	{
		$recordModel = \Vtiger_Record_Model::getInstanceById($record, $module);
		$data = \App\Json::decode($recordModel->get($field));
		\Vtiger_MultiImage_UIType::deleteRecord($recordModel);
		$this->assertFileNotExists($data[0]['path'], 'File should be removed');
		$recordModel->set($field, \App\Json::encode([]));
		$recordModel->save();
		$recordModel = \Vtiger_Record_Model::getInstanceById($record, $module);
		$this->assertSame(\App\Json::encode([]), $recordModel->get($field), 'Value should be empty');
	}

	/**
	 * Add multi image test.
	 *
	 * @throws \App\Exceptions\AppException
	 */
	public function testAddMultiImage()
	{
		$recordModel = \Vtiger_Record_Model::getInstanceById(\Tests\Base\C_RecordActions::createProductRecord()->getId(), 'Products');
		$attach = [];
		foreach (static::$files as $i => $name) {
			$filePathSrc = 'tests' . \DIRECTORY_SEPARATOR . 'data' . \DIRECTORY_SEPARATOR . 'MultiImage' . \DIRECTORY_SEPARATOR . $name;
			$filePathDst = 'tests' . \DIRECTORY_SEPARATOR . 'tmp' . \DIRECTORY_SEPARATOR . 'MultiImage' . \DIRECTORY_SEPARATOR . md5(rand(0, 9999)) . substr($name, \strpos($name, '.'));
			\copy($filePathSrc, $filePathDst);
			$fileObj = \App\Fields\File::loadFromPath($filePathDst);
			$hash[$i] = $fileObj->generateHash(true, $filePathDst);
			$attach[$i] = [
				'name' => $name,
				'size' => \vtlib\Functions::showBytes($fileObj->getSize()),
				'key' => $hash[$i],
				'path' => $fileObj->getPath()
			];
			$size[$i] = \vtlib\Functions::showBytes($fileObj->getSize());
		}
		$recordModel->set('imagename', \App\Json::encode($attach));
		$recordModel->save();
		$recordModel = \Vtiger_Record_Model::getInstanceById($recordModel->getId(), 'Products');
		$fieldModel = $recordModel->getField('imagename');
		$data = \App\Json::decode(\App\Purifier::decodeHtml($fieldModel->getUITypeModel()->getDisplayValueEncoded($recordModel->get('imagename'), $recordModel->getId(), $fieldModel->getFieldInfo()['limit'])));
		$this->assertNotEmpty($data);
		foreach (static::$files as $i => $name) {
			$this->assertSame($name, $data[$i]['name'], 'File name should be equal');
			$this->assertSame($attach[$i]['size'], $data[$i]['size'], 'File size should be equal');
			$this->assertFileExists($attach[$i]['path'], 'File should exists');
			$this->assertSame($attach[$i]['key'], $data[$i]['key'], 'Key should be equal');
			parse_str(\parse_url($data[$i]['imageSrc'])['query'], $url);
			$this->assertSame('Products', $url['module'], 'Module in image url should be equal to provided');
			$this->assertSame('imagename', $url['field'], 'Field name in image url should be equal to provided');
			$this->assertSame($data[$i]['key'], $url['key'], 'Key in image url should be equal to provided');
			$this->assertSame((string) $recordModel->getId(), $url['record'], 'Record in image url should be equal to provided');
		}
		\Vtiger_MultiImage_UIType::deleteRecord($recordModel);
		foreach (\App\Json::decode($recordModel->get('imagename')) as $info) {
			$this->assertFileNotExists($info['path'], 'File should be removed');
		}
	}

	/**
	 * @codeCoverageIgnore
	 * Cleaning after tests.
	 */
	public static function tearDownAfterClass()
	{
		\vtlib\Functions::recurseDelete('tests' . \DIRECTORY_SEPARATOR . 'tmp' . \DIRECTORY_SEPARATOR . 'MultiImage' . \DIRECTORY_SEPARATOR, true);
	}
}
