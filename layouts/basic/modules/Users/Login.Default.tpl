{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): YetiForce.com
********************************************************************************/
-->*}
{strip}
	{assign var="MODULE" value='Users'}
	<div class="container">
		<div id="login-area" class="login-area">
			<div class="login-space"></div>
			<div class="logo">
				<img title="{$COMPANY_DETAILS->get('name')}" height="{$COMPANY_DETAILS->get('logo_login_height')}px" class="logo" src="{$COMPANY_DETAILS->getLogo('logo_login')->get('imageUrl')}" alt="{$COMPANY_DETAILS->get('name')}">
			</div>
			<div class="" id="loginDiv">
				{if !$IS_BLOCKED_IP}
					<form class="login-form" action="index.php?module=Users&action=Login" method="POST" {if !AppConfig::security('LOGIN_PAGE_REMEMBER_CREDENTIALS')}autocomplete="off"{/if}>
						<div class='fieldContainer marginLeft0 marginRight0 row col-md-12'>
							<div class='marginLeft0  marginRight0 col-sm-10'>
								<label for="username" class="sr-only">{\App\Language::translate('LBL_USER',$MODULE)}</label>
								<div class="input-group form-group first-group">
									<input name="username" type="text" id="username" class="form-control form-control-lg" {if \AppConfig::main('systemMode') === 'demo'}value="demo"{/if} placeholder="{\App\Language::translate('LBL_USER',$MODULE)}" required="" {if !AppConfig::security('LOGIN_PAGE_REMEMBER_CREDENTIALS')}autocomplete="off"{/if} autofocus="">
									<div class="input-group-append">
										<div class="input-group-text"><i class="fas fa-user" aria-hidden="true"></i></div>
									</div>
								</div>
								<label for="password" class="sr-only">{\App\Language::translate('Password',$MODULE)}</label>
								<div class="input-group form-group {if $LANGUAGE_SELECTION || $LAYOUT_SELECTION}first-group {/if}">
									<input name="password" type="password" class="form-control form-control-lg" title="{\App\Language::translate('Password',$MODULE)}" id="password" name="password" {if \AppConfig::main('systemMode') === 'demo'}value="demo"{/if} {if !AppConfig::security('LOGIN_PAGE_REMEMBER_CREDENTIALS')}autocomplete="off"{/if} placeholder="{\App\Language::translate('Password',$MODULE)}">
									<div class="input-group-append">
										<div class="input-group-text"><i class="fa fa-briefcase" aria-hidden="true"></i></div>
									</div>
								</div>
								{assign var=COUNTERFIELDS value=2}
								{if $LANGUAGE_SELECTION}
									{assign var=COUNTERFIELDS value=$COUNTERFIELDS+1}
									{assign var=DEFAULT_LANGUAGE value=AppConfig::main('default_language')}
									<div class="input-group form-group {if $LAYOUT_SELECTION}first-group {/if}">
										<select class="form-control-lg form-control" title="{\App\Language::translate('LBL_CHOOSE_LANGUAGE',$MODULE)}" name="loginLanguage">
											{foreach item=VALUE key=KEY from=\App\Language::getAll()}
												<option {if $KEY eq $DEFAULT_LANGUAGE} selected {/if}  value="{\App\Purifier::encodeHtml($KEY)}">{$VALUE}</option>
											{/foreach}
										</select>
										<div class="input-group-append">
											<div class="input-group-text"><i class="fa fa-language" aria-hidden="true"></i></div>
										</div>
									</div>
								{/if}
								{if $LAYOUT_SELECTION}
									{assign var=COUNTERFIELDS value=$COUNTERFIELDS+1}
									<div class="form-group">
										<select class="form-control-lg form-control" title="{\App\Language::translate('LBL_SELECT_LAYOUT',$MODULE)}" name="layout">
											{foreach item=VALUE key=KEY from=\App\Layout::getAllLayouts()}
												<option value="{\App\Purifier::encodeHtml($KEY)}">{$VALUE}</option>
											{/foreach}
										</select>	
									</div>
								{/if}
							</div>
							<div class='col-sm-2 marginRight0' >
								<button class="btn btn-lg btn-primary btn-block heightDiv_{$COUNTERFIELDS}" type="submit" title="{\App\Language::translate('LBL_SIGN_IN', $MODULE_NAME)}">
									<strong>></strong>
								</button>
							</div>
						</div>
					</form>
					{if AppConfig::security('RESET_LOGIN_PASSWORD') && App\Mail::getDefaultSmtp()}
						<div class="form-group">
							<div class="">
								<a href="#" id="forgotpass" >{\App\Language::translate('ForgotPassword',$MODULE)}?</a>
							</div>
						</div>
					{/if}
				{/if}
				<div class="form-group col-xs-12 noPadding">
					{if $MESSAGE}
						<div class="alert {if $MESSAGE_TYPE === 'success'}alert-success{elseif $MESSAGE_TYPE === 'error'}alert-danger{else}alert-warning{/if}">
							<p>{$MESSAGE}</p>
						</div>
					{/if}
					{if $IS_BLOCKED_IP}
						<div class="alert alert-danger">
							<div class="row">
								<div class="col-md-2"><span style="font-size: 60px;" class="fa fa-minus-circle" aria-hidden="true"></span></div>
								<div class="col-md-10"><p>{\App\Language::translate('LBL_IP_IS_BLOCKED',$MODULE_NAME)}</p></div>
							</div>
						</div>
					{/if}
				</div>
			</div>	
			{if AppConfig::security('RESET_LOGIN_PASSWORD') && App\Mail::getDefaultSmtp()}
				<div class="hide" id="forgotPasswordDiv">
					<form class="forgot-form" action="index.php?module=Users&action=ForgotPassword" method="POST">
						<div class='fieldContainer marginLeft0 marginRight0 row col-md-12'>
							<div class='marginLeft0  marginRight0 row col-sm-10'>	
								<label for="usernameFp" class="sr-only">{\App\Language::translate('LBL_USER',$MODULE)}</label>
								<div class="input-group form-group first-group">
									<input type="text" class="form-control form-control-lg" title="{\App\Language::translate('LBL_USER',$MODULE)}" id="usernameFp" name="user_name" placeholder="{\App\Language::translate('LBL_USER',$MODULE)}">
									<div class="input-group-append">
										<div class="input-group-text adminIcon-user"></div>
									</div>
								</div>
								<label for="emailId" class="sr-only">{\App\Language::translate('LBL_EMAIL',$MODULE)}</label>
								<div class="input-group form-group">
									<input type="text" class="form-control form-control-lg" autocomplete="off" title="{\App\Language::translate('LBL_EMAIL',$MODULE)}" id="emailId" name="emailId" placeholder="Email">
									<div class="input-group-append">
										<div class="input-group-text fas fa-envelope"></div>
									</div>
								</div>
							</div>
							<div class='col-sm-2 marginRight0' >
								<button type="submit" style='height:102px' id="retrievePassword" class="btn btn-lg btn-primary btn-block sbutton" title="Retrieve Password">
									{*\App\Language::translate('LBL_SEND',$MODULE)*}
									<strong>></strong>
								</button>
							</div>
						</div>
					</form>
					<div class="login-text form-group">
						<a href="#" id="backButton" >{\App\Language::translate('LBL_TO_CRM',$MODULE)}</a>
					</div>
				</div>
			{/if}
		</div>
	</div>
	<script>
		jQuery(document).ready(function () {
			jQuery("button.close").click(function () {
				jQuery(".visible-phone").css('visibility', 'hidden');
			});
			jQuery("a#forgotpass").click(function () {
				jQuery("#loginDiv").hide();
				jQuery("#forgotPasswordDiv").removeClass('hide');
				jQuery("#forgotPasswordDiv").show();
			});
			jQuery("a#backButton").click(function () {
				jQuery("#loginDiv").removeClass('hide');
				jQuery("#loginDiv").show();
				jQuery("#forgotPasswordDiv").hide();
			});
			jQuery("form.forgot-form").submit(function (event) {
				if ($("#usernameFp").val() === "" || $("#emailId").val() === "") {
					event.preventDefault();
				}
			});
		});
	</script>
{/strip}
