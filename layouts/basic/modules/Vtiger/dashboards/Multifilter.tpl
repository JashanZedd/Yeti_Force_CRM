{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	{assign var=ACCESSIBLE_USERS value=\App\Fields\Owner::getInstance()->getAccessibleUsers()}
	{assign var=ACCESSIBLE_GROUPS value=\App\Fields\Owner::getInstance()->getAccessibleGroups()}
	{assign var=CURRENTUSERID value=$USER_MODEL->getId()}
	<div class="js-multifilterControls tpl-dashboards-Multifilter dashboardWidgetHeader"
		 data-js="container|data-widgetid"
		 data-widgetid="{$WIDGET->get('id')}">
		<div class="row">
			<div class="col-md-8">
				<div class="dashboardTitle" title="{\App\Language::translate($WIDGET->getTitle(), $MODULE_NAME)}">
					<strong>{\App\Language::translate($WIDGET->getTitle(), $MODULE_NAME)}</strong></div>
			</div>
			<div class="col-md-4">
				<div class="box float-right	">
					{include file=\App\Layout::getTemplatePath('dashboards/DashboardHeaderIcons.tpl', $MODULE_NAME)}
				</div>
			</div>
		</div>
		<hr class="widgetHr"/>
		<div class="row no-gutters">
			<div class="col-sm-12">
				<div class="input-group input-group-sm">
					<span class="input-group-addon"><span
								class="glyphicon glyphicon-filter iconMiddle mt-2"></span></span>
					<select class="widgetFilter form-control customFilter input-sm" multiple="multiple"
							name="customMultiFilter" title="{\App\Language::translate('LBL_CUSTOM_FILTER')}">
						{assign var=CUSTOM_VIEWS value=CustomView_Record_Model::getAll()}
						{foreach key=GROUP_LABEL item=GROUP_CUSTOM_VIEWS from=$CUSTOM_VIEWS}
							{foreach item="CUSTOM_VIEW" from=$GROUP_CUSTOM_VIEWS}
								{if !(\App\Privilege::isPermitted({$GROUP_CUSTOM_VIEWS->module->name}))}
									{continue}
								{/if}
								<option title="{\App\Language::translate($GROUP_CUSTOM_VIEWS->module->name)}"
										data-module="{$GROUP_CUSTOM_VIEWS->module->name}"
										value="{$GROUP_CUSTOM_VIEWS->get('cvid')}" {if $DATA['customFilter'] eq $GROUP_CUSTOM_VIEWS->get('cvid')} selected {/if}>
									{\App\Language::translate($GROUP_CUSTOM_VIEWS->module->name,$GROUP_CUSTOM_VIEWS->module->name)}
									&nbsp;-&nbsp;{\App\Language::translate($GROUP_CUSTOM_VIEWS->get('viewname'), $GROUP_CUSTOM_VIEWS->module->name)}
								</option>
							{/foreach}
						{/foreach}
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="dashboardWidgetContent">
		<div class="js-multifilterContent contents" data-js="container">
		</div>
	</div>
{/strip}
