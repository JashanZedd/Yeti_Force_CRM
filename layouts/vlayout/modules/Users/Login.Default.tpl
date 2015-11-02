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
{assign var="CompanyDetails" value=getCompanyDetails()}
{assign var="MODULE" value='Users'}
<div class="container">
	<div id="login-area" class="login-area">
		{if $ENABLED_MOBILE_MODULE}
			<div class="visible-phone">
				<div class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>{vtranslate('LBL_MOBILE_VERSION_TITLE',$MODULE)}</h4>
					{vtranslate('LBL_MOBILE_VERSION_DESC',$MODULE)}
					<a class="btn btn-primary" href="modules/Mobile/">{vtranslate('LBL_MOBILE_VERSION_BUTTON',$MODULE)}</a>
				</div>
			</div>
		{else}
			<div class="login-space"></div>
		{/if}	
		<div class="logo">
			<img title="{$CompanyDetails['companyname']}" class="img-responsive logo" src="storage/Logo/{$CompanyDetails['logoname']}" alt="{$CompanyDetails['companyname']}">
		</div>
		<div class="" id="loginDiv">
			<form class="login-form" action="index.php?module=Users&action=Login" method="POST">
				<div class="form-group first-group has-feedback">
					<label for="username" class="sr-only">{vtranslate('LBL_USER',$MODULE)}</label>
					<input name="username" type="text" id="username" class="form-control input-lg" {if vglobal('systemMode') == 'demo'}value="demo"{/if} placeholder="{vtranslate('LBL_USER',$MODULE)}" required="" autofocus="">
					<span class="glyphicon glyphicon-user form-control-feedback" aria-hidden="true"></span>
				</div>
				<div class="form-group {if $LANGUAGE_SELECTION}first-group {/if}has-feedback">
					<label for="username" class="sr-only">{vtranslate('Password',$MODULE)}</label>
					<input name="password" type="password" class="form-control input-lg" title="{vtranslate('Password',$MODULE)}" id="password" name="password" {if vglobal('systemMode') == 'demo'}value="demo"{/if} placeholder="{vtranslate('Password',$MODULE)}">
					<span class="glyphicon glyphicon-lock form-control-feedback" aria-hidden="true"></span>
				</div>	
				{if $LANGUAGE_SELECTION}
					<div class="form-group">
						<select class="input-lg form-control" name="language">
							{foreach item=VALUE key=KEY from=Vtiger_Language_Handler::getAllLanguages()}
								<option value="{Vtiger_Util_Helper::toSafeHTML($KEY)}">{$VALUE}</option>
							{/foreach}
						</select>	
					</div>
				{/if}
				<button class="btn btn-lg btn-primary btn-block" type="submit" title="{vtranslate('LBL_SIGN_IN', $MODULE_NAME)}">
					{vtranslate('LBL_SIGN_IN', $MODULE_NAME)}
				</button>
			</form>
			<div class="form-group">
				{if SysSecurity::get('RESET_LOGIN_PASSWORD')}
					<div class="">
						<a href="#" id="forgotpass" >{vtranslate('ForgotPassword',$MODULE)}?</a>
					</div>
				{/if}
			</div>
			<div class="form-group">
				{if $ERROR eq 1}
					<div class="alert alert-warning">
						<p>{vtranslate('Invalid username or password.',$MODULE)}</p>
					</div>
				{/if}
				{if $ERROR eq 2}
					<div class="alert alert-warning">
						<p>{vtranslate('Too many failed login attempts.',$MODULE)}</p>
					</div>
				{/if}
				{if $FPERROR}
					<div class="alert alert-warning">
						<p>{vtranslate('Invalid Username or Email address.',$MODULE)}</p>
					</div>
				{/if}
				{if $STATUS}
					<div class="alert alert-success">
						<p>{vtranslate('Mail has been sent to your inbox, please check your e-mail.',$MODULE)}</p>
					</div>
				{/if}
				{if $STATUS_ERROR}
					<div class="alert alert-warning">
						<p>{vtranslate('Outgoing mail server was not configured.',$MODULE)}</p>
					</div>
				{/if}
			</div>
		</div>
		{if SysSecurity::get('RESET_LOGIN_PASSWORD')}
			<div class="hide" id="forgotPasswordDiv">
				<form class="login-form" action="modules/Users/actions/ForgotPassword.php" method="POST">
					<div class="form-group first-group has-feedback">
						<label for="username" class="sr-only">{vtranslate('LBL_USER',$MODULE)}</label>
						<input type="text" class="form-control input-lg" title="{vtranslate('LBL_USER',$MODULE)}" id="username" name="user_name" placeholder="{vtranslate('LBL_USER',$MODULE)}">
						<span class="glyphicon glyphicon-user form-control-feedback" aria-hidden="true"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="emailId" class="sr-only">{vtranslate('LBL_EMAIL',$MODULE)}</label>
						<input type="text" class="form-control input-lg" autocomplete="off" title="{vtranslate('LBL_EMAIL',$MODULE)}" id="emailId" name="emailId" placeholder="Email">
						<span class="glyphicon glyphicon-envelope form-control-feedback" aria-hidden="true"></span>
					</div>
					<button type="submit" id="retrievePassword" class="btn btn-lg btn-primary btn-block sbutton" title="Retrieve Password">
						{vtranslate('LBL_SEND',$MODULE)}
					</button>
				</form>
				<div class="form-group">
					<a href="#" id="backButton" >{vtranslate('LBL_TO_CRM',$MODULE)}</a>
				</div>
			</div>	
		{/if}
	</div>
</div>
<script>
	jQuery(document).ready(function(){
		jQuery("button.close").click(function() {
			jQuery(".visible-phone").css('visibility', 'hidden');
		});
		jQuery("a#forgotpass").click(function() {
			jQuery("#loginDiv").hide();
			jQuery("#forgotPasswordDiv").removeClass('hide');
			jQuery("#forgotPasswordDiv").show();
		});
		
		jQuery("a#backButton").click(function() {
			jQuery("#loginDiv").removeClass('hide');
			jQuery("#loginDiv").show();
			jQuery("#forgotPasswordDiv").hide();
		});
		
		jQuery("input[name='retrievePassword']").click(function (){
			var username = jQuery('#user_name').val();
			var email = jQuery('#emailId').val();
			var email1 = email.replace(/^\s+/,'').replace(/\s+$/,'');
			var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
			var illegalChars= /[\(\)\<\>\,\;\:\\\"\[\]]/ ;
			
			if(username == ''){
				alert('Please enter valid username');
				return false;
			} else if(!emailFilter.test(email1) || email == ''){
				alert('Please enater valid email address');
				return false;
			} else if(email.match(illegalChars)){
				alert( "The email address contains illegal characters.");
				return false;
			} else {
				return true;
			}
		});
	});
</script>
{/strip}
