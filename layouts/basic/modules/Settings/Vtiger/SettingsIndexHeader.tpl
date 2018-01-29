{strip}
	<div class="widget_header row ">
		<div class="col-xs-12">
			{include file=\App\Layout::getTemplatePath('BreadCrumbs.tpl', $MODULE)}
		</div>
	</div>
	<div class="row no-margin">
		<ul class="nav nav-tabs massEditTabs">
			{*<li  data-mode="DonateUs"><a data-toggle="tab"><strong>{\App\Language::translate('LBL_DONATE_US', $QUALIFIED_MODULE)}</strong></a></li>*}
			<li class="active nav-item" data-mode="index" data-params="{\App\Purifier::encodeHtml(\App\Json::encode(['count'=>$WARNINGS_COUNT]))}"><a class="nav-link" data-toggle="tab"><strong>{\App\Language::translate('LBL_START', $QUALIFIED_MODULE)}</strong></a></li>
			<li class="nav-item" data-mode="github"><a class="nav-link" data-toggle="tab"><strong>{\App\Language::translate('LBL_GITHUB', $QUALIFIED_MODULE)}</strong></a></li>
			<li class="nav-item" data-mode="systemWarnings"><a class="nav-link" data-toggle="tab"><strong>{\App\Language::translate('LBL_SYSTEM_WARNINGS', $QUALIFIED_MODULE)}</strong></a></li>
			<li class="nav-item" data-mode="security"><a class="nav-link" data-toggle="tab"><strong>{\App\Language::translate('LBL_SECURITY', $QUALIFIED_MODULE)}</strong></a></li>
		</ul>
	</div>
	<div class="indexContainer"></div>
{/strip}
