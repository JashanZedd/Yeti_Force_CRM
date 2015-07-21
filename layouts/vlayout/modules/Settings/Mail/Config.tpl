<div class=" configContainer" style="margin-top:10px;">
	<h3>{vtranslate('LBL_MAIL_GENERAL_CONFIGURATION', $QUALIFIED_MODULE)}</h3>&nbsp;{vtranslate('LBL_MAIL_GENERAL_CONFIGURATION_DESCRIPTION', $QUALIFIED_MODULE)}<hr>
	{assign var=ALL_ACTIVEUSER_LIST value=$USER_MODEL->getAccessibleUsers()}
	<ul id="tabs" class="nav nav-tabs nav-justified" data-tabs="tabs">
		<li class="active"><a href="#configuration" data-toggle="tab">{vtranslate('LBL_MAIL_ICON_CONFIG', $QUALIFIED_MODULE)}</a></li>
		<li><a href="#signature" data-toggle="tab">{vtranslate('LBL_SIGNATURE', $QUALIFIED_MODULE)}</a></li>
	</ul>
	<br />
	<div class="tab-content">
		<div class="tab-pane active" id="configuration">
			{assign var=CONFIG value=$MODULE_MODEL->getConfig('mailIcon')}
			<div class="row">
				<div class="col-md-1 pagination-centered">
					<input class="configCheckbox" type="checkbox" name="showMailIcon" id="showMailIcon" data-type="mailIcon" value="1" {if $CONFIG['showMailIcon']=='true'}checked=""{/if}>
				</div>
				<div class="col-md-11">
					<label for="showMailIcon">{vtranslate('LBL_SHOW_MAIL_ICON', $QUALIFIED_MODULE)}</label>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1 pagination-centered">
					<input class="configCheckbox" type="checkbox" name="showMailAccounts" id="showMailAccounts" data-type="mailIcon" value="1" {if $CONFIG['showMailAccounts']=='true'}checked=""{/if}>
				</div>
				<div class="col-md-11">
					<label for="showMailAccounts">{vtranslate('LBL_SHOW_MAIL_ACCOUNTS', $QUALIFIED_MODULE)}</label>
				</div>
			</div>
			<div class="row">
				<div class="col-md-1 pagination-centered">
					<input class="configCheckbox" type="checkbox" name="showNumberUnreadEmails" id="showNumberUnreadEmails" data-type="mailIcon" value="1" {if $CONFIG['showNumberUnreadEmails']=='true'}checked=""{/if}>
				</div>
				<div class="col-md-11">
					<label for="showNumberUnreadEmails">{vtranslate('LBL_NUMBER_UNREAD_EMAILS', $QUALIFIED_MODULE)}</label>
				</div>
			</div>
		</div>
		<div class="tab-pane" id="signature">
			{assign var=CONFIG_SIGNATURE value=$MODULE_MODEL->getConfig('signature')}
			<div class="row">
				<div class="col-md-1 pagination-centered">
					<input class="configCheckbox" type="checkbox" name="addSignature" id="addSignature" data-type="signature" value="1" {if $CONFIG_SIGNATURE['addSignature']=='true'}checked=""{/if}>
				</div>
				<div class="col-md-11">
					<label for="addSignature">{vtranslate('LBL_ADD_SIGNATURE', $QUALIFIED_MODULE)}</label>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="col-md-12">
					<textarea id="signatureCkEditor" class="ckEditorSource" name="signature">{$CONFIG_SIGNATURE['signature']}</textarea>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-12">
					<button class="btn btn-success pull-right"><strong>{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</strong></button>
				</div>
			</div>
		</div>
	</div>
</div>
