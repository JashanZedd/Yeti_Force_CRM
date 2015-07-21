	{if $PAINTEDICON eq 1}
		
		<ul class="settingIcons nav navbar-nav navbar-right">
			<li class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle" href="#">
					<img src="{vimage_path('theme_brush.png')}" alt="theme roller" title="Theme Roller" />
				</a>
				<ul class="dropdown-menu themeMenuContainer">
					<div id="themeContainer">
						{assign var=COUNTER value=0}
						{assign var=THEMES_LIST value=Vtiger_Theme::getAllSkins()}
						<div class="row themeMenu">
							{foreach key=SKIN_NAME item=SKIN_COLOR from=$THEMES_LIST}
							{if $COUNTER eq 3}
						</div>
						<div class="row themeMenu">
							{assign var=COUNTER value=1}
							{else}
							{assign var=COUNTER value=$COUNTER+1}
							{/if}
							<div class="col-md-4 themeElement {if $USER_MODEL->get('theme') eq $SKIN_NAME}themeSelected{/if}" data-skin-name="{$SKIN_NAME}" title="{ucfirst($SKIN_NAME)}" style="background-color:{$SKIN_COLOR};"></div>
							{/foreach}
						</div>
					</div>
					<div id="progressDiv"></div>
				</ul>
			</li>
		</ul>
	{/if}
	{foreach key=index item=obj from=$HEADER_LINKS}
		{assign var="src" value=$obj->getIconPath()}
		{assign var="icon" value=$obj->getIcon()}
		{assign var="title" value=$obj->getLabel()}
		{assign var="childLinks" value=$obj->getChildLinks()}
		<ul class="{if !empty($src)} settingIcons {/if} nav navbar-nav navbar-right">
			<li class="dropdown">
			{if !empty($src)}
				<a id="menubar_item_right_{$title}" class="dropdown-toggle" data-toggle="dropdown" href="#"><img src="{$src}" alt="{vtranslate($title,$MODULE)}" title="{vtranslate($title,$MODULE)}" /></a>
			{else}
				{assign var=title value=$USER_MODEL->get('first_name')|cat:' '|cat:$USER_MODEL->get('last_name')}
				<a id="menubar_item_right_{$title}"  class="userName dropdown-toggle" data-toggle="dropdown" href="#" title="{$title}"><strong>{$title}</strong><span class="caret"></span> </a>
			{/if}
			{if !empty($childLinks)}
				<ul class="dropdown-menu pull-right">
					{foreach key=index item=obj from=$childLinks}
						{if $obj->getLabel() eq NULL}
							<li class="divider"></li>
						{else}
							{assign var="id" value=$obj->getId()}
							{assign var="href" value=$obj->getUrl()}
							{assign var="label" value=$obj->getLabel()}
							{assign var="onclick" value=""}
							{if stripos($obj->getUrl(), 'javascript:') === 0}
								{assign var="onclick" value="onclick="|cat:$href}
								{assign var="href" value="javascript:;"}
							{/if}
							<li>
								<a target="{$obj->target}" id="menubar_item_right_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($label)}" {if $label=='Switch to old look'}switchLook{/if} href="{$href}" {$onclick}>{vtranslate($label,$MODULE)}</a>
							</li>
						{/if}
					{/foreach}
				</ul>
			{/if}
			</li>
		</ul>
	{/foreach}
	<ul class="headerLinksContainer nav navbar-nav navbar-right">
		<li>
			<div class="remindersNotice">
				<span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
				<span class="badge hide">0</span>
			</div>
		</li>
	</ul>
{if $CHAT_ACTIVE eq true}
	<ul class="headerLinksContainer headerLinksAJAXChat nav navbar-nav navbar-right">
		<li>
			<a class="ChatIcon" href="#"><img src="layouts/vlayout/skins/images/chat.png" alt="chat_icon"/></a>
		</li>
	</ul>
{/if}
{assign var=CONFIG value=Settings_Mail_Config_Model::getConfig('mailIcon')}
{assign var=AUTOLOGINUSERS value=OSSMail_Autologin_Model::getAutologinUsers()}
{if $CONFIG['showMailIcon']=='true' && count($AUTOLOGINUSERS) > 0}
	<ul class="nav navbar-nav navbar-right headerLinksContainer headerLinksMails" id="OSSMailBoxInfo" {if $CONFIG['showNumberUnreadEmails']=='true'}data-numberunreademails="true" data-interval="{$CONFIG['timeCheckingMail']}"{/if}>
		<li class="btn-group">
			{assign var=MAIN_MAIL value=OSSMail_Module_Model::getDefaultMailAccount($AUTOLOGINUSERS)}
			<a {if $CONFIG['showMailAccounts']=='true'}class="dropdown-toggle mainMail" data-toggle="dropdown" href="#" {else} class="mainMail" href="index.php?module=OSSMail&view=index" {/if} title="{$MAIN_MAIL.username}"><span class="mail_user_name btn-default btn-sm ">{$MAIN_MAIL.username}</span> <span class="noMails_{$MAIN_MAIL.rcuser_id}"></span></a>
			{if $CONFIG['showMailAccounts']=='true'}
				<ul class="dropdown-menu" role="menu">
					{foreach key=KEY item=ITEM from=$AUTOLOGINUSERS}
						<li data-id="{$KEY}" {if $ITEM.active}selested{/if}><a href="#">{$ITEM.username} <span class="noMails"></span></a></li>
					{/foreach}
				</ul>
			{/if}
		</li>
	</ul>
{/if}
<div id="headerLinksCompact" class="hide">
	<span id="dropdown-headerLinksBig" class="dropdown">
		<a class="dropdown-toggle navbar-btn" data-toggle="dropdown" href="#">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
		<ul class="dropdown-menu pull-right">
			{foreach key=index item=obj from=$HEADER_LINKS name="compactIndex"}
				{assign var="src" value=$obj->getIconPath()}
				{assign var="icon" value=$obj->getIcon()}
				{assign var="title" value=$obj->getLabel()}
				{assign var="childLinks" value=$obj->getChildLinks()}
				{if $smarty.foreach.compactIndex.index neq 0}
					<li class="divider"></li>
				{/if}
				{foreach key=index item=obj from=$childLinks}
					{assign var="id" value=$obj->getId()}
					{assign var="href" value=$obj->getUrl()}
					{assign var="label" value=$obj->getLabel()}
					{assign var="onclick" value=""}
					{if stripos($obj->getUrl(), 'javascript:') === 0}
						{assign var="onclick" value="onclick="|cat:$href}
						{assign var="href" value="javascript:;"}
					{/if}
					<li>
						<a target="{$obj->target}" id="menubar_item_right_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($label)}" {if $label=='Switch to old look'}switchLook{/if} href="{$href}" {$onclick}>{vtranslate($label,$MODULE)}</a>
					</li>
				{/foreach}
			{/foreach}
		</ul>
	</span>
</div>
