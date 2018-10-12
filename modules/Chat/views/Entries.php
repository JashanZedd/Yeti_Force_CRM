<?php

/**
 * Chat Entries View Class.
 *
 * @package   View
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 * @author    Arkadiusz Adach <a.adach@yetiforce.com>
 */
class Chat_Entries_View extends Vtiger_IndexAjax_View
{
	/**
	 * Construct.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->exposeMethod('get');
	}

	/**
	 * Get entries function.
	 *
	 * @param \App\Request $request
	 *
	 * @throws \App\Exceptions\IllegalValue
	 */
	public function get(\App\Request $request)
	{
		$room = \App\Chat::getInstanceById(
			$request->has('chat_room_id') ? $request->getInteger('chat_room_id') : \App\Chat::getCurrentRoomId()
		);
		$response = new Vtiger_Response();
		$response->setResult([
			'success' => true,
			'html' => (new self())->getHTML($request, $room),
			'room_id' => $room->getRoomId()
		]);
		$response->emit();
	}

	/**
	 * Get HTML.
	 *
	 * @param \App\Request   $request
	 * @param \App\Chat|null $room
	 *
	 * @throws \App\Exceptions\IllegalValue
	 *
	 * @return \html|string
	 */
	public function getHTML(\App\Request $request, \App\Chat $room = null)
	{
		if (empty($room)) {
			$room = \App\Chat::getInstanceById(
				$request->has('chat_room_id') ? $request->getInteger('chat_room_id') : \App\Chat::getCurrentRoomId()
			);
		}
		$items = $room->getEntries($request->getInteger('cid'));
		if (count($items)) {
			if ($request->has('visible') && $request->getBoolean('visible')) {
				$room->setLastMessage();
			}
			$viewer = Vtiger_Viewer::getInstance();
			$viewer->assign('CHAT_ENTRIES', $items);
			return $viewer->view('Items.tpl', 'Chat', true);
		}
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function isSessionExtend()
	{
		return false;
	}
}
