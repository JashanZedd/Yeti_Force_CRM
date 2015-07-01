{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->*}
{strip}
{assign var=MODULE_NAME value="Calendar"}
	{if count($ACTIVITIES) neq '0'}
		{foreach item=RECORD key=KEY from=$ACTIVITIES}
			{assign var=START_DATE value=$RECORD->get('date_start')}
			{assign var=START_TIME value=$RECORD->get('time_start')}
			{assign var=END_DATE value=$RECORD->get('due_date')}
			{assign var=END_TIME value=$RECORD->get('time_end')}
			{assign var=EDITVIEW_PERMITTED value=isPermitted('Calendar', 'EditView', $RECORD->get('crmid'))}
			{assign var=DETAILVIEW_PERMITTED value=isPermitted('Calendar', 'DetailView', $RECORD->get('crmid'))}
			<div class="activityEntries">
				<input type="hidden" class="activityId" value="{$RECORD->get('activityid')}"/>
				<div class="row">
					<span class="col-md-6">
						<strong title='{Vtiger_Util_Helper::formatDateTimeIntoDayString("$START_DATE $START_TIME")}'><span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;{Vtiger_Util_Helper::formatDateIntoStrings($START_DATE, $START_TIME)}</strong>
					</span>
					<span class="col-md-6 rightText">
						<strong title='{Vtiger_Util_Helper::formatDateTimeIntoDayString("$END_DATE $END_TIME")}'><span class="glyphicon glyphicon-time"></span>&nbsp;&nbsp;{Vtiger_Util_Helper::formatDateIntoStrings($END_DATE, $END_TIME)}</strong>
					</span>
				</div>
				<div class="summaryViewEntries">
					{if $RECORD->get('activitytype') == 'Task'}
						<image src="{vimage_path('Tasks.png')}" width="12px" alt="{vtranslate($RECORD->get('activitytype'),$MODULE_NAME)}"/>&nbsp;&nbsp;
					{elseif $RECORD->get('activitytype') == 'Meeting'}
						<image src="{vimage_path('Meeting.png')}" width="12px" alt="{vtranslate($RECORD->get('activitytype'),$MODULE_NAME)}"/>&nbsp;&nbsp;
					{else}
						<image src="{vimage_path('Call.png')}" width="12px" alt="{vtranslate($RECORD->get('activitytype'),$MODULE_NAME)}"/>&nbsp;&nbsp;
					{/if}
					{vtranslate($RECORD->get('activitytype'),$MODULE_NAME)}&nbsp;-&nbsp; 
					{if $DETAILVIEW_PERMITTED == 'yes'}<a href="{$RECORD->getDetailViewUrl()}" >{$RECORD->get('subject')}</a>{else}{$RECORD->get('subject')}{/if}&nbsp;
					{if $EDITVIEW_PERMITTED == 'yes'}<a href="{$RECORD->getEditViewUrl()}" class="fieldValue"><span class="glyphicon glyphicon-pencil summaryViewEdit" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}"></span></a>{/if}
				</div>
				<div class="row">
					<div class="activityStatus col-md-12">
						{if $RECORD->get('activitytype') eq 'Task'}
							{assign var=MODULE_NAME value=$RECORD->getModuleName()}
							<input type="hidden" class="activityModule" value="{$RECORD->getModuleName()}"/>
							<input type="hidden" class="activityType" value="{$RECORD->get('activitytype')}"/>
							<div>
								<strong><span class="icon-tags"></span>&nbsp&nbsp;<span class="value">{vtranslate($RECORD->get('status'),$MODULE_NAME)}</span></strong>&nbsp&nbsp;
								<span class="editStatus cursorPointer"><span class="glyphicon glyphicon-pencil" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}"></span></span>
								<span class="1 edit hide">
									{assign var=FIELD_MODEL value=$RECORD->getModule()->getField('taskstatus')}
									{assign var=FIELD_VALUE value=$FIELD_MODEL->set('fieldvalue', $RECORD->get('status'))}
									{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME OCCUPY_COMPLETE_WIDTH=false}
									<input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}' data-prev-value='{$FIELD_MODEL->get('fieldvalue')}' />
								</span>
								{if $DATA_TYPE != 'history'}
									<span class="editDefaultStatus pull-right cursorPointer popoverTooltip delay0" data-field="{$FIELD_MODEL->get('name')}" data-status="Completed" data-content="{vtranslate('LBL_MARK_AS_DONE',$MODULE_NAME)}"><span class="glyphicon glyphicon-ok"></span></span>
								{/if}
							</div>
						{else}
							{assign var=MODULE_NAME value="Events"}
							<input type="hidden" class="activityModule" value="Events"/>
							<input type="hidden" class="activityType" value="{$RECORD->get('activitytype')}"/>
							{if $EDITVIEW_PERMITTED == 'yes'}
								<div>
									<strong><span class="icon-tags"></span>&nbsp&nbsp;<span class="value">{vtranslate($RECORD->get('eventstatus'),$MODULE_NAME)}</span></strong>&nbsp&nbsp;
									<span class="editStatus cursorPointer"><span class="glyphicon glyphicon-pencil" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}"></span></span>
									<span class="2 edit hide">
										{assign var=FIELD_MODEL value=$RECORD->getModule()->getField('eventstatus')}
										{assign var=FIELD_VALUE value=$FIELD_MODEL->set('fieldvalue', $RECORD->get('eventstatus'))}
										{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME OCCUPY_COMPLETE_WIDTH=false}
										 {if $FIELD_MODEL->getFieldDataType() eq 'multipicklist'}
											<input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}[]' data-prev-value='{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}' />
										 {else}
											 <input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}' data-prev-value='{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}' />
										 {/if}
									</span>
									{if $DATA_TYPE != 'history'}
										<span class="editDefaultStatus pull-right cursorPointer popoverTooltip delay0" data-field="{$FIELD_MODEL->get('name')}" data-status="Held" data-content="{vtranslate('LBL_MARK_AS_DONE',$MODULE_NAME)}"><span class="glyphicon glyphicon-ok"></span></span>
									{/if}
								</div>
							{/if}
						{/if}
					</div>
				</div>
				<div class="activityDescription">					    
					<div>
						<span class="value"><span class="glyphicon glyphicon-align-justify"></span>&nbsp&nbsp;
						{if $RECORD->get('description') neq ''}
							{vtranslate($RECORD->get('description'),$MODULE_NAME)|truncate:120:'...'}
						{else}
							<span class="muted">{vtranslate('LBL_NO_DESCRIPTION',$MODULE_NAME)}</span>
						{/if}
						</span>&nbsp&nbsp;
						<span class="editDescription cursorPointer"><span class="glyphicon glyphicon-pencil" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}"></span></span>
						{if $EDITVIEW_PERMITTED == 'yes'}
							<span class="2 edit hide">
								{assign var=FIELD_MODEL value=$RECORD->getModule()->getField('description')}
								{assign var=FIELD_VALUE value=$FIELD_MODEL->set('fieldvalue', $RECORD->get('description'))}
								{include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME OCCUPY_COMPLETE_WIDTH=false}
								 {if $FIELD_MODEL->getFieldDataType() eq 'multipicklist'}
									<input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}[]' data-prev-value='{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}' />
								 {else}
									 <input type="hidden" class="fieldname" value='{$FIELD_MODEL->get('name')}' data-prev-value='{$FIELD_MODEL->getDisplayValue($FIELD_MODEL->get('fieldvalue'))}' />
								 {/if}
							</span>
						{/if}
						{if $RECORD->get('status')}
							{assign var=STATUS value=$RECORD->get('status')}
						{else}
							{assign var=STATUS value=$RECORD->get('eventstatus')}
						{/if}
						<span class="pull-right popoverTooltip delay0" data-placement="top" data-original-title="{vtranslate($RECORD->get('activitytype'),$MODULE_NAME)}: {$RECORD->get('subject')}" 
						data-content="{vtranslate('Status',$MODULE_NAME)}: {vtranslate($STATUS,$MODULE_NAME)}<br />{vtranslate('Start Time','Calendar')}: {$START_DATE} {$START_TIME}<br />{vtranslate('End Time','Calendar')}: {$END_DATE} {$END_TIME}<hr />{vtranslate('Created By',$MODULE_NAME)}: {Vtiger_Functions::getOwnerRecordLabel( $RECORD->get('smcreatorid') )}<br />{vtranslate('Assigned To',$MODULE_NAME)}: {Vtiger_Functions::getOwnerRecordLabel( $RECORD->get('smownerid') )}
						{if count($RECORD->get('selectedusers')) > 0}
							<br />{vtranslate('LBL_INVITE_USER_BLOCK',$MODULE_NAME)}: 
							{foreach item=USER key=KEY from=$RECORD->get('selectedusers')}
								{Vtiger_Functions::getOwnerRecordLabel( $USER )}
							{/foreach}
						{/if}" >
							<span class="glyphicon glyphicon-info-sign"></span>
						</span>
						{if $RECORD->get('location') neq '' }
							<a target="_blank" href="https://www.google.com/maps/search/{urlencode ($RECORD->get('location'))}" class="pull-right popoverTooltip delay0" data-original-title="{vtranslate('Location', 'Calendar')}" data-content="{$RECORD->get('location')}">
								<span class="icon-map-marker"></span>&nbsp
							</a>
						{/if}
					</div>
				</div>
			</div>
							<hr>
		{/foreach}
	{else}
		<div class="summaryWidgetContainer">
			<p class="textAlignCenter">{vtranslate('LBL_NO_PENDING_ACTIVITIES',$MODULE_NAME)}</p>
		</div>
	{/if}
	{if $PAGING_MODEL->isNextPageExists()}
		<div class="row">
			<div class="pull-right">
				<a href="javascript:void(0)" class="moreRecentActivities">{vtranslate('LBL_MORE',$MODULE_NAME)}..</a>
			</div>
		</div>
	{/if}
{/strip}
