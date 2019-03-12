{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<!-- tpl-Calendar-InviteRow -->
	{assign var=LABEL value=''}
	{if !isset($INVITIE)}
		{assign var=INVITIE value=['crmid'=>'','inviteesid'=>'','email'=>'','status'=>'','time'=>'']}
	{/if}
	{if $INVITIE['crmid']}
		{assign var=LABEL value=$INVITIE['label']}
		{assign var=NAME value=$INVITIE['label']}
		{assign var=TITLE value=\App\Language::translateSingularModuleName($INVITIE['setype'])|cat:': '|cat:$LABEL|cat:' - '|cat:$INVITIE['email']}
		{assign var=ICON value='userIcon-'|cat:$INVITIE['setype']}
	{elseif empty($INVITIE['name'])}
		{assign var=LABEL value=$INVITIE['email']}
		{assign var=NAME value=''}
		{assign var=TITLE value=$INVITIE['email']}
		{assign var=ICON value='fas fa-envelope'}
	{else}
		{assign var=LABEL value=$INVITIE['name']}
		{assign var=NAME value=$INVITIE['name']}
		{assign var=TITLE value=$INVITIE['name']|cat:': '|cat:$INVITIE['email']}
		{assign var=ICON value='fas fa-envelope'}
	{/if}
	<div class="inviteRow" data-crmid="{$INVITIE['crmid']}" data-ivid="{$INVITIE['inviteesid']}" data-email="{$INVITIE['email']}" data-name="{\App\Purifier::encodeHtml($NAME)}">
		<div class="input-group input-group-sm">
			<span class="input-group-prepend inviteIcon">
				<span class="input-group-text">
					<span class="{$ICON}"></span>
				</span>
				<span class="input-group-text u-w-125px u-max-w-150px text-truncate inviteName {if $TITLE}js-popover-tooltip{/if}" data-js="popover" data-content="{$TITLE}">{$LABEL}</span>
				<span class="input-group-text inviteStatus">
					{assign var=STATUS_LABEL value=Calendar_Record_Model::getInvitionStatus($INVITIE['status'])}
					{if $INVITIE['status'] == '1'}
						<span class="fas fa-check-circle js-popover-tooltip" data-js="popover" data-placement="top" data-content="{\App\Language::translate($STATUS_LABEL, $MODULE_NAME)} {if $INVITIE['time']}({DateTimeField::convertToUserFormat($INVITIE['time'])}){/if}"></span>
{elseif $INVITIE['status'] == '2'}
						<span class="fas fa-minus-circle js-popover-tooltip" data-js="popover" data-placement="top" data-content="{\App\Language::translate($STATUS_LABEL, $MODULE_NAME)} {if $INVITIE['time']}({DateTimeField::convertToUserFormat($INVITIE['time'])}){/if}"></span>
{else}
	{assign var=LABEL value=$INVITIE['email']}
						<span class="fas fa-question-circle js-popover-tooltip" data-js="popover" data-placement="top" data-content="{\App\Language::translate($STATUS_LABEL, $MODULE_NAME)}"></span>
					{/if}
				</span>
			</span>
			<span class="input-group-append">
				<button class="btn btn-outline-secondary border inviteRemove" type="button">
					<span class="fas fa-times"></span>
				</button>
			</span>
		</div>
	</div>
	<!-- /tpl-Calendar-InviteRow -->
{/strip}
