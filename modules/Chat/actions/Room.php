<?php

/**
 * Chat Entries Action Class.
 *
 * @package   Action
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 * @author    Arkadiusz Adach <a.adach@yetiforce.com>
 */
class Chat_Room_Action extends \App\Controller\Action
{
	use \App\Controller\ExposeMethod;

	/**
	 * Constructor with a list of allowed methods.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('getAll');
		$this->exposeMethod('create');
		$this->exposeMethod('removeFromFavorites');
		$this->exposeMethod('addToFavorites');
	}

	/**
	 * Function to check permission.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\NoPermitted
	 */
	public function checkPermission(\App\Request $request)
	{
		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if (!$currentUserPriviligesModel->hasModulePermission($request->getModule())) {
			throw new \App\Exceptions\NoPermitted('ERR_NOT_ACCESSIBLE', 406);
		}
	}

	/**
	 * Add entries function.
	 *
	 * @param \App\Request $request
	 */
	public function getAll(\App\Request $request)
	{
		$response = new Vtiger_Response();
		$response->setResult([
			'currentRoom' => \App\Chat::getCurrentRoom(),
			'roomList' => \App\Chat::getRoomsByUser()
		]);
		$response->emit();
	}

	/**
	 * Create new room.
	 *
	 * @param \App\Request $request
	 */
	public function create(\App\Request $request)
	{
		$recordModel = Vtiger_Record_Model::getInstanceById($request->getInteger('recordId'));
		if (!$recordModel->isViewable()) {
			throw new \App\Exceptions\NoPermittedToRecord('ERR_NO_PERMISSIONS_FOR_THE_RECORD', 406);
		}
		\App\Chat::createRoom($request->getByType('roomType'), $recordModel->getId());
		$response = new Vtiger_Response();
		$response->setResult([
		]);
		$response->emit();
	}

	/**
	 * Remove from favorites.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\IllegalValue
	 * @throws \yii\db\Exception
	 */
	public function removeFromFavorites(\App\Request $request)
	{
		\App\Chat::getInstance($request->getByType('roomType'), $request->getInteger('recordId'))->removeFromFavorites();
		$response = new Vtiger_Response();
		$response->setResult([
		]);
		$response->emit();
	}

	/**
	 * Add to favorites.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\IllegalValue
	 * @throws \yii\db\Exception
	 */
	public function addToFavorites(\App\Request $request)
	{
		\App\Chat::getInstance($request->getByType('roomType'), $request->getInteger('recordId'))->addToFavorites();
		$response = new Vtiger_Response();
		$response->setResult([
		]);
		$response->emit();
	}
}
