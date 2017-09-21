{*<!-- {[The file is published on the basis of YetiForce Public License 2.0 that can be found in the following directory: licenses/License.html or yetiforce.com]} -->*}
{strip}
	{if $WIZARD_STEP eq 'step1'}
		<div id="minilistWizardContainer" class='modelContainer modal fade' tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header contentsBackground">
						<button data-dismiss="modal" class="close" title="{\App\Language::translate('LBL_CLOSE')}">&times;</button>
						<h3 class="modal-title" id="massEditHeader">{\App\Language::translate('LBL_MINI_LIST', $MODULE)} {\App\Language::translate($MODULE, $MODULE)}</h3>
					</div>
					<form class="form-horizontal" method="post" action="javascript:;">
						<div class="modal-body">
							<input type="hidden" name="module" value="{$MODULE}" />
							<input type="hidden" name="action" value="MassSave" />
							<input type="hidden" id="widgetStep" value="" />
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_WIDGET_NAME')}</td>
										<td class="fieldValue">
											<input type="text" class="form-control" name="widgetTitle" value="">
										</td>
									</tr>
									<tr>
										<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_SELECT_CHART')}</td>
										<td class="fieldValue">
											<div class="input-group">
												<select class="form-control select2" name="chartType">
													{foreach from=$CHART_TYPES item=TYPE key=VALUE}
														<option value="{$VALUE}">{\App\Language::translate($TYPE, $MODULE)}</option>
													{/foreach}
												</select>
												<span class="input-group-addon hide isColorContainer">
													<input type="checkbox" class="isColor popoverTooltip" data-content="{\App\Language::translate('LBL_CHART_COLOR_DESCRIPTION',$MODULE)}">
												</span>
											</div>
										</td>
									</tr>
									<tr class="step1">
										<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_SELECT_MODULE')}</td>
										<td class="fieldValue">
											<select class="form-control" name="module">
												<option></option>
												{foreach from=$MODULES item=MODULE_MODEL key=MODULE_NAME}
													<option value="{$MODULE_MODEL['name']}">{\App\Language::translate($MODULE_MODEL['name'], $MODULE_MODEL['name'])}</option>
												{/foreach}
											</select>
										</td>
									</tr>
									<tr class="step2"></tr>
									<tr class="step3"></tr>
									<tr class="step4 hide sectorContainer">
										<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_SECTOR')}</td>
										<td class="fieldValue">
											<select class="form-control select2" multiple name="sectorField" size="2" >
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						{include file=\App\Layout::getTemplatePath('ModalFooter.tpl', $MODULE)}
					</form>
				</div>
			</div>
		</div>
	{elseif $WIZARD_STEP eq 'step2'}
		<tr class="step2">
			<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_FILTER')}</td>
			<td class="fieldValue">
				<select class="form-control filterId" name="filterid">
					<option></option>
					{foreach from=$ALLFILTERS item=FILTERS key=FILTERGROUP}
						<optgroup label="{\App\Language::translate($FILTERGROUP,$SELECTED_MODULE)}">
							{foreach from=$FILTERS item=FILTER key=FILTERNAME}
								{if $FILTER->get('setmetrics') eq 1}
									<option value="{$FILTER->getId()}">{\App\Language::translate($FILTER->get('viewname'),$SELECTED_MODULE)}</option>
								{/if}
							{/foreach}
						</optgroup>
					{/foreach}
				</select>
			</td>
		</tr>
	{elseif $WIZARD_STEP eq 'step3'}
		<tr class="step3">
			<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_GROUP_FIELD')}</td>
			<td class="fieldValue">
				<select class="form-control groupField" name="groupField" size="2" >
					<option></option>
					{foreach from=$MODULE_FIELDS item=FIELD key=FIELD_NAME}
						<option value="{$FIELD_NAME}" data-field-type="{$FIELD->getFieldDataType()}">{\App\Language::translate($FIELD->getFieldLabel(),$SELECTED_MODULE)}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	{elseif $WIZARD_STEP eq 'step4'}
		{if $CHART_TYPE == 'Funnel' && in_array($GROUP_FIELD_MODEL->getFieldDataType(),['currency', 'double', 'percentage', 'integer'])}
			<tr class="step4">
				<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_SECTOR')}</td>
				<td class="fieldValue">
					<select class="form-control select saveParam" multiple name="sectorField" size="2"></select>
				</td>
			</tr>
		{/if}
		{if $CHART_TYPE != '' && in_array($CHART_TYPE,['Area','Axis'])}
			<tr class="step4">
				<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_TIMELINE_BY_DATE')}</td>
				<td class="fieldValue">
					<select class="form-control saveParam" name="timeLine" size="2" >
						<option>{\App\Language::translate('--None--')}</option>
						{foreach from=$SELECTED_MODULE_MODEL->getFieldsByType(['date','datetime']) item=FIELD key=FIELD_NAME}
							<option value="{$FIELD_NAME}">{\App\Language::translate($FIELD->getFieldLabel(),$SELECTED_MODULE)}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		{/if}
		{if $CHART_TYPE != '' && in_array($CHART_TYPE,['Bardivided'])}
			<tr class="step4">
				<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_BAR_DIVIDED_FIELD')}</td>
				<td class="fieldValue">
					<select class="form-control saveParam" name="barDividedField" size="2" >
						<option>{\App\Language::translate('--None--')}</option>
						{foreach from=$SELECTED_MODULE_MODEL->getFields() item=FIELD key=FIELD_NAME}
							<option value="{$FIELD_NAME}">{\App\Language::translate($FIELD->getFieldLabel(),$SELECTED_MODULE)}</option>
						{/foreach}
					</select>
				</td>
			</tr>
		{/if}
		<tr class="step4">
			<td class="fieldLabel alignMiddle textAlignCenter" nowrap>{\App\Language::translate('LBL_FILTERING_BY_DATE')}</td>
			<td class="fieldValue">
				<select class="form-control saveParam" name="timeRange" size="2" >
					<option>{\App\Language::translate('--None--')}</option>
					{foreach from=$SELECTED_MODULE_MODEL->getFieldsByType(['date','datetime']) item=FIELD key=FIELD_NAME}
						<option value="{$FIELD_NAME}">{\App\Language::translate($FIELD->getFieldLabel(),$SELECTED_MODULE)}</option>
					{/foreach}
				</select>
			</td>
		</tr>
	{/if}
{/strip}
