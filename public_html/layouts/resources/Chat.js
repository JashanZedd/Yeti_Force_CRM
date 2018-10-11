/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */
'use strict';

/**
 * Class Chat_Js.
 * @type {Window.Chat_Js}
 */
window.Chat_Js = class Chat_Js {
	/**
	 * Get instance of Chat_Js.
	 * @returns {Chat_Js|Window.Chat_Js}
	 */
	static getInstance() {
		if (typeof Chat_Js.instance === 'undefined') {
			Chat_Js.instance = new Chat_Js();
		}
		return Chat_Js.instance;
	}

	/**
	 * Constructor of class.
	 */
	constructor() {
		this.chatRoom = [];
	}

	/**
	 * Add new item to room list.
	 * @param {int} roomId
	 * @param {string} name
	 */
	addRoomItem(roomId, name) {
		let template = $('.js-chat-modal .js-room-template');
		if (template.length) {
			let item = template.clone(false, false);
			item.removeClass('hide');
			item.removeClass('js-room-template');
			item.removeClass(item.data('selectedClass'));
			item.find('.js-change-room').html(name);
			item.data('roomId', roomId);
			$('.js-chat-modal .js-chat-rooms-list').append(item);
			this.registerSwitchRoom($('.js-chat-modal'));
			this.registerRemoveRoom($('.js-chat-modal'));
		}
	}

	/**
	 * Remove room item.
	 * @param {int} roomId
	 */
	removeRoomItem(roomId) {
		$('.js-chat-modal .js-remove-room').each((index, element) => {
			if ($(element).closest('.row').data('roomId') == roomId) {
				$(element).closest('.row').remove();
			}
		});
	}

	/**
	 * Update room.
	 * @param {jQuery} container
	 * @param {jQuery} itemRoom
	 * @param {string} html
	 * @param {int} roomId
	 */
	updateRoom(container, itemRoom, html, roomId) {
		let prevChatRoomId = container.data('chatRoomId');
		let selectedClass = itemRoom.closest('.row').data('selectedClass');
		container.find('.js-chat-items').html(html);
		container.data('chatRoomId', roomId);
		itemRoom.closest('.js-chat-modal').find('.js-change-room').each((index, element) => {
			if (roomId == $(element).closest('.row').data('roomId')) {
				$(element).closest('.row').removeClass(selectedClass).addClass(selectedClass);
				container.find('.js-chat-items').closest('.row')
					.removeClass('js-chat-room-' + prevChatRoomId)
					.addClass('js-chat-room-' + roomId);
			} else {
				$(element).closest('.row').removeClass(selectedClass);
			}
		});
	}

	/**
	 * Register switch room.
	 * @param {jQuery} container
	 */
	registerSwitchRoom(container) {
		const self = this;
		container.find('.js-change-room').off('click').on('click', (e) => {
			let itemRoom = $(e.currentTarget);
			let roomId = $(e.currentTarget).closest('.row').data('roomId');
			const progressIndicatorElement = $.progressIndicator({
				'position': 'html',
				'blockInfo': {
					'enabled': true
				}
			});
			AppConnector.request({
				dataType: 'json',
				data: {
					module: 'Chat',
					action: 'Entries',
					mode: 'switchRoom',
					chat_room_id: roomId
				}
			}).done((dataResult) => {
				let html = ''
				if (typeof dataResult !== 'undefined') {
					html = dataResult.result.html;
				}
				self.updateRoom(container, itemRoom, html, roomId);
				progressIndicatorElement.progressIndicator({'mode': 'hide'});
			}).fail((error, err) => {
				app.errorLog(error, err);
			});
		});

	}

	/**
	 * Register remove room.
	 * @param {jQuery} container
	 */
	registerRemoveRoom(container) {
		const self = this;
		container.find('.js-remove-room').off('click').on('click', (e) => {
			let itemRoom = $(e.currentTarget);
			let roomId = $(e.currentTarget).closest('.row').data('roomId');
			const progressIndicatorElement = $.progressIndicator({
				'position': 'html',
				'blockInfo': {
					'enabled': true
				}
			});
			AppConnector.request({
				dataType: 'json',
				data: {
					module: 'Chat',
					action: 'Entries',
					mode: 'removeRoom',
					chat_room_id: roomId
				}
			}).done((dataResult) => {
				if (typeof dataResult !== 'undefined') {
					self.updateRoom(container, itemRoom, dataResult.result.html, dataResult.result['chat_room_id']);
					itemRoom.closest('.row').remove();
				}
				progressIndicatorElement.progressIndicator({'mode': 'hide'});
			}).fail((error, err) => {
				app.errorLog(error, err);
			});
		});
	}

	/**
	 * Add chat room to record.
	 *
	 * @param {jQuery} container
	 */
	addRoom(container) {
		const chatRoomId = container.data('chatRoomId');
		if (typeof chatRoomId !== 'undefined') {
			const self = this;
			AppConnector.request({
				module: 'Chat',
				action: 'Entries',
				mode: 'addRoom',
				record: chatRoomId,
			}).done((data) => {
				if (data && data.success) {
					if (container.find('.js-container-button').hasClass('hide')) {
						container.find('.js-container-button').removeClass('hide');
						container.find('.js-container-items').addClass('hide');
					} else {
						container.find('.js-container-button').addClass('hide');
						container.find('.js-container-items').removeClass('hide');
					}
					self.addRoomItem(data.result['chat_room_id'], data.result['name']);
				}
			}).fail((error, err) => {
				app.errorLog(error, err);
			});
		} else {
			app.errorLog(new Error("Unknown chat room id"));
		}
	}

	/**
	 * Update chat.
	 * @param {int} chatRoomId
	 * @param {html} html
	 */
	updateChat(container, chatRoomId, html) {
		if (html) {
			let chatRoom = container.find('.js-chat-room-' + chatRoomId);
			$(html).insertBefore(chatRoom.find('.chatItem').first());
		}
	}

	/**
	 * Get last chat ID.
	 * @param {jQuery} container
	 * @returns {int}
	 */
	getLastChatId(container) {
		return container.find('.js-chat-items .chatItem').first().data('cid');
	}

	/**
	 * Send chat message.
	 * @param {jQuery} container
	 * @param {jQuery} inputMessage
	 */
	sendMessage(container, inputMessage) {
		if (inputMessage.val() == '') {
			return;
		}
		const chatRoomId = container.data('chatRoomId');
		if (typeof chatRoomId !== 'undefined') {
			const self = this;
			const chatItems = container.find('.js-chat-items');
			let icon = container.find('.modal-title .fa-comments');
			icon.css('color', '#00e413');
			AppConnector.request({
				dataType: 'json',
				data: {
					module: 'Chat',
					action: 'Entries',
					mode: 'addMessage',
					message: inputMessage.val(),
					cid: self.getLastChatId(container),
					chat_room_id: chatRoomId
				}
			}).done((dataResult) => {
				self.updateChat(container, chatRoomId, dataResult.result.html);
				inputMessage.val("");
				icon.css('color', '#000');
				if (dataResult.result['user_added_to_room']) {
					self.addRoomItem(dataResult.result['room']['room_id'], dataResult.result['room']['name']);
				}
			}).fail((error, err) => {
				app.errorLog(error, err);
			});
		} else {
			app.errorLog(new Error("Unknown chat room id"));
		}
	}

	/**
	 * Get chat items.
	 * @param {jQuery} container
	 */
	getChatItems(container) {
		const chatRoomId = container.data('chatRoomId');
		const chatItems = container.find('.js-chat-items');
		const self = this;
		if (typeof chatRoomId !== 'undefined') {
			AppConnector.request({
				dataType: 'json',
				data: {
					module: 'Chat',
					view: 'Entries',
					mode: 'get',
					cid: self.getLastChatId(container),
					chat_room_id: chatRoomId
				}
			}).done((dataResult) => {
				if (dataResult.result.success) {
					if (
						dataResult.result['room_id'] == chatRoomId &&
						container.find('.js-container-button').length &&
						!container.find('.js-container-button').hasClass('hide')
					) {
						container.find('.js-container-button').addClass('hide');
						container.find('.js-container-items').removeClass('hide');
					}
					self.updateChat(container, chatRoomId, dataResult.result.html);
				}
			}).fail((error, err) => {
				clearTimeout(self.chatRoom[container.data('chatRoomIdx')]);
			});
		} else {
			app.errorLog(new Error("Unknown chat room id"));
		}
	}

	/**
	 * Register chat load items.
	 * @param {jQuery} container
	 */
	registerChatLoadItems(container) {
		const self = this;
		self.chatRoom[container.data('chatRoomIdx')] = setTimeout(() => {
			self.getChatItems(container);
			self.registerChatLoadItems(container);
		}, container.data('timer'));
	}

	/**
	 * Register header link chat
	 */
	registerHeaderLinkChat() {
		$('.headerLinkChat').on('click', (e) => {
			e.stopPropagation();
			let remindersNoticeContainer = $('.remindersNoticeContainer,.remindersNotificationContainer');
			if (remindersNoticeContainer.hasClass('toggled')) {
				remindersNoticeContainer.removeClass('toggled');
			}
			$('.actionMenu').removeClass('actionMenuOn');
			$('.chatModal').modal({backdrop: false});
		});
	}

	registerChatCheck(timer, container) {
		const self = this;
		self.chatCheckTimer = setTimeout(() => {
			self.registerChatCheck(timer, container);
		}, timer);
	}

	/**
	 * Toggle favorite.
	 * @param {jQuery} container
	 */
	registerToggleFavorite(container) {
		container.find('.js-chat-favorite').on('click', (e) => {
			let button = $(e.currentTarget);
			let favorite = button.data('favorite');
			const self = this;
			const progressIndicatorElement = $.progressIndicator({
				'position': 'html',
				'blockInfo': {
					'enabled': true
				}
			});
			AppConnector.request({
				dataType: 'json',
				data: {
					module: 'Chat',
					action: 'Entries',
					mode: 'addRoomToFavorite',
					chat_room_id: container.data('chatRoomId'),
					favorite: favorite
				}
			}).done((dataResult) => {
				if (typeof dataResult !== 'undefined' && dataResult.result.success) {
					if (dataResult.result['favorite']) {
						button.removeClass('btn-success').addClass('btn-danger');
						self.addRoomItem(dataResult.result['chat_room_id'], dataResult.result['name_of_room']);
					} else {
						button.removeClass('btn-danger').addClass('btn-success');
						self.removeRoomItem(dataResult.result['chat_room_id']);
					}
					button.data('favorite', !dataResult.result['favorite']);
				}
				progressIndicatorElement.progressIndicator({'mode': 'hide'});
			}).fail((error, err) => {
				app.errorLog(error, err);
			});
		});
	}

	/**
	 * Register chat events
	 * @param {jQuery} container
	 */
	registerEvents(container = $('.js-chat-modal')) {
		if (container.length) {
			const self = this;
			container.data('chat-room-idx', self.chatRoom.length);
			container.find('.js-create-chatroom').on('click', (e) => {
				self.addRoom(container);
			});
			container.find('.js-chat-message').on('keydown', (e) => {
				if (e.keyCode === 13) {
					e.preventDefault();
					self.sendMessage(container, $(e.currentTarget));
					return false;
				}
			});
			self.registerToggleFavorite(container);
			self.registerChatLoadItems(container);
			let modal = container.closest('.chatModal');
			if (modal.length) {
				self.registerSwitchRoom(container);
				self.registerRemoveRoom(container);
				self.registerHeaderLinkChat();
				app.showNewScrollbar(modal.find('.modal-body'), {wheelPropagation: true});
				app.animateModal(modal, 'slideInRight', 'slideOutRight');
			}
		}
	}
}
/**
 * Create chat instance and register events.
 */
$(document).ready((e) => {
	const instance = Chat_Js.getInstance();
	instance.registerEvents();
});
