{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<div class="dashboardWidgetHeader">
		<div class="row">
			<div class="col-md-8">
				<h5 class="dashboardTitle h6" title="{App\Purifier::encodeHtml(App\Language::translate($WIDGET->getTitle(), 'Home'))}">
					<strong class="d-block js-popover-tooltip--ellipsis" data-content="{App\Purifier::encodeHtml(App\Language::translate($WIDGET->getTitle(), $MODULE_NAME))}" data-toggle="popover" data-js="tooltip">&nbsp;&nbsp;{\App\Language::translate($WIDGET->getTitle(), 'Home')}</strong>
				</h5>
			</div>
			<div class="col-md-4">
				<div class="box float-right">
					{include file=\App\Layout::getTemplatePath('dashboards/DashboardHeaderIcons.tpl', $MODULE_NAME)}
				</div>
			</div>
		</div>
		<hr class="widgetHr" />
		<div class="row justify-content-end">
			<div class="col-md-6">
				<div class="input-group input-group-sm flex-nowrap">
					<div class="input-group-prepend">
						<span class="input-group-text">
							<span class="fas fa-bell fa-fw"></span>
						</span>
					</div>
					<div class="select2Wrapper">
						<select class="widgetFilter form-control select2" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="type">
							{foreach from=$TYPES_NOTIFICATION key=KEY item=TYPE}
								<option value="{$KEY}">{$TYPE}</option>
							{/foreach}
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="btn-toolbar float-right">
					{if \App\Privilege::isPermitted('Notification', 'CreateView')}
						<button type="button" class="btn btn-light" onclick="Vtiger_Index_Js.sendNotification()" title="{\App\Language::translate('LBL_ADD_RECORD')}" alt="{\App\Language::translate('LBL_ADD_RECORD')}">
							<span class="fas fa-plus"></span>
						</button>
					{/if}
					<button type="button"  class="btn btn-light" href="index.php?module=Notification&view=List" title="{\App\Language::translate('LBL_GO_TO_RECORDS_LIST')}" alt="{\App\Language::translate('LBL_GO_TO_RECORDS_LIST')}">
						<span class="fas fa-th-list"></span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="dashboardWidgetContent">
		{include file=\App\Layout::getTemplatePath('dashboards/NotificationsContents.tpl', $MODULE_NAME)}
	</div>
{/strip}
