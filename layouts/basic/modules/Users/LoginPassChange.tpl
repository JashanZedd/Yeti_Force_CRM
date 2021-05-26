{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
<!-- tpl-Users-Login-LoginPassChange -->
<div class="container">
<div id="login-area" class="login-area">
	<div class="login-space"></div>
	<div class="logo mb-3">
		<img class="logo__img" title="Logo" class="logo" alt="Logo" src="{\App\Layout::getPublicUrl('layouts/resources/Logo/logo')}">
	</div>
	<div id="loginDiv">
		{if isset($MESSAGE)}
			<div class="form-group col-12 p-0">
				<div class="alert alert-danger">
					<div class="col-md-12"><p>{\App\Language::translate($MESSAGE,$MODULE_NAME)}</p>
				</div>
			</div>
		{else}
			<form class="login-form row js-change-password" data-js="container">
				<input name="token" type="hidden" value="{$TOKEN}">
				<div class='fieldContainer mx-0 form-row col-md-12'>
					<div class='mx-0 col-sm-10'>
						<label for="password" class="sr-only">{\App\Language::translate('LBL_NEW_PASSWORD',$MODULE)}</label>
						<div class="input-group form-group first-group">
							<input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="{\App\Language::translate('LBL_NEW_PASSWORD',$MODULE)}" required="">
						</div>
						<label for="confirm_password" class="sr-only">{\App\Language::translate('LBL_CONFIRM_PASSWORD',$MODULE)}</label>
						<div class="input-group form-group">
							<input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg" placeholder="{\App\Language::translate('LBL_CONFIRM_PASSWORD',$MODULE)}" required="">
						</div>
					</div>
					<div class="col-sm-2">
						<button type="submit" class="btn btn-lg btn-primary btn-block heightDiv_2" title="Retrieve Password">
							<strong><span class="fas fa-chevron-right"></span></strong>
						</button>
					</div>
				</div>
			</form>
		{/if}
	</div>
</div>
</div>
</body>
</html>
<!-- /tpl-Users-Login-LoginPassChange -->
{/strip}
