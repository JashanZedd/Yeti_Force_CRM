{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
<div class="dashboardWidgetHeader">
	<div class="row">
		<div class="col-md-8">
			<div class="dashboardTitle" title="{\App\Language::translate($WIDGET->getTitle(), $MODULE_NAME)}"><strong>&nbsp;&nbsp;{\App\Language::translate($WIDGET->getTitle(),$MODULE_NAME)}</strong></div>
		</div>
		<div class="col-md-4">
			<div class="box float-right">
				{if \App\Privilege::isPermitted('Accounts', 'CreateView')}
					<a class="btn btn-sm btn-light" onclick="Vtiger_Header_Js.getInstance().quickCreateModule('Accounts');
							return false;" title="{\App\Language::translate('LBL_ADD_RECORD')}" alt="{\App\Language::translate('LBL_ADD_RECORD')}">
						<span class='fas fa-plus' border='0'></span>
					</a>
				{/if}
				<a class="btn btn-sm btn-light" href="javascript:void(0);" name="drefresh" data-url="{$WIDGET->getUrl()}&linkid={$WIDGET->get('linkid')}&content=data" title="{\App\Language::translate('LBL_REFRESH')}" alt="{\App\Language::translate('LBL_REFRESH')}">
					<span class="fas fa-sync-alt" hspace="2" border="0" align="absmiddle"></span>
				</a>
				{if !$WIDGET->isDefault()}
					<a class="btn btn-sm btn-light" name="dclose" class="widget" data-url="{$WIDGET->getDeleteUrl()}" title="{\App\Language::translate('LBL_CLOSE')}" alt="{\App\Language::translate('LBL_CLOSE')}">
						<span class="fas fa-times" hspace="2" border="0" align="absmiddle"></span>
					</a>
				{/if}
			</div>
		</div>
	</div>
	<hr class="widgetHr" />
	<div class="row" >
		<div class="col-sm-6">
			<div class="input-group input-group-sm">
				<div class=" input-group-prepend">
					<span class="input-group-text u-cursor-pointer js-date__btn" data-js="click">
						<span class="fas fa-calendar-alt"></span>
					</span>
				</div>
				<input type="text" name="time" title="{\App\Language::translate('LBL_CHOOSE_DATE')}" class="dateRangeField widgetFilter form-control text-center" value="{implode(',',$DTIME)}" />
			</div>	
		</div>
		<div class="col-sm-6">
			{include file=\App\Layout::getTemplatePath('dashboards/SelectAccessibleTemplate.tpl', $MODULE_NAME)}
		</div>
	</div>
</div>

<div class="dashboardWidgetContent">
	{include file=\App\Layout::getTemplatePath('dashboards/NewAccountsContents.tpl', $MODULE_NAME)}
</div>

