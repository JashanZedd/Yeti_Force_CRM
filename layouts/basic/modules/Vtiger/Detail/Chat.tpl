{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<!-- tpl-Base-Detail-Chat -->
	<script type="application/javascript">
		$(document).ready((e) => {
			Chat_Js.getInstance().registerEvents($('.js-chat-detail'));
		});
	</script>
	<div class="js-chat-detail" data-chat-room-id="{$RECORD_MODEL->getId()}"
		 data-timer="{AppConfig::module('Chat', 'REFRESH_TIME')}"
		 data-js="container">
		<div class="{if $CHAT->isRoomExists()}hide {/if}js-container-button">
			<button type="button" class="btn btn-success js-create-chatroom" data-js="click">
				<span class="fa fa-plus mr-2" title="{\App\Language::translate('LBL_CREATE', $MODULE_NAME)}"></span>
				{\App\Language::translate('LBL_CREATE_CHAT_ROOM')}
			</button>
		</div>
		<div class="{if !$CHAT->isRoomExists()}hide {/if}js-container-items">
			{include file=\App\Layout::getTemplatePath('Detail/ChatInput.tpl') BTN_FAVORITE=true}
			<div class="js-chat-items js-chat-room-{$RECORD_MODEL->getId()} pr-2" data-js="html">
				{include file=\App\Layout::getTemplatePath('Items.tpl', 'Chat') CHAT_ENTRIES=$CHAT->getEntries($CHAT_ID)}
			</div>
		</div>
	</div>
	<!-- /tpl-Base-Detail-Chat -->
{/strip}
