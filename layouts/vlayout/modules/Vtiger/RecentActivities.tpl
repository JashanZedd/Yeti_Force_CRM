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
<div class="recentActivitiesContainer" id="updates">
    <input type="hidden" id="updatesCurrentPage" value="{$PAGING_MODEL->get('page')}" />
    
	<div>
		{if !empty($RECENT_ACTIVITIES)}
			<ul class="list-unstyled">
				{foreach item=RECENT_ACTIVITY from=$RECENT_ACTIVITIES}
					{assign var=PROCEED value= TRUE}
					{if ($RECENT_ACTIVITY->isRelationLink()) or ($RECENT_ACTIVITY->isRelationUnLink())}
						{assign var=RELATION value=$RECENT_ACTIVITY->getRelationInstance()}
						{if !($RELATION->getLinkedRecord())}
							{assign var=PROCEED value= FALSE}
						{/if}
					{/if}
					{if $PROCEED}
						{if $RECENT_ACTIVITY->isCreate()}
							<li>
								<div>
									<span><strong>{$RECENT_ACTIVITY->getModifiedBy()->getName()}</strong> {vtranslate('LBL_CREATED', $MODULE_NAME)}</span>
									<span class="pull-right"><p class="muted"><small title="{Vtiger_Util_Helper::formatDateTimeIntoDayString($RECENT_ACTIVITY->getParent()->get('createdtime'))}">{Vtiger_Util_Helper::formatDateDiffInStrings($RECENT_ACTIVITY->getParent()->get('createdtime'))}</small></p></span>
								</div>
							</li>
						{else if $RECENT_ACTIVITY->isUpdate()}
							<li>
								<div>
									<span><strong>{$RECENT_ACTIVITY->getModifiedBy()->getDisplayName()}</strong> {vtranslate('LBL_UPDATED', $MODULE_NAME)}</span>
									<span class="pull-right"><p class="muted"><small title="{Vtiger_Util_Helper::formatDateTimeIntoDayString($RECENT_ACTIVITY->getActivityTime())}">{Vtiger_Util_Helper::formatDateDiffInStrings($RECENT_ACTIVITY->getActivityTime())}</small></p></span>
								</div>
	
								{foreach item=FIELDMODEL from=$RECENT_ACTIVITY->getFieldInstances()}
									{if $FIELDMODEL && $FIELDMODEL->getFieldInstance() && $FIELDMODEL->getFieldInstance()->isViewable() && $FIELDMODEL->getFieldInstance()->getDisplayType() neq '5'}
										<div class='font-x-small updateInfoContainer'>
											<span>{vtranslate($FIELDMODEL->getName(),$MODULE_NAME)}</span> :&nbsp;
												{if $FIELDMODEL->get('prevalue') neq '' && $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && ($FIELDMODEL->get('postvalue') eq '0' || $FIELDMODEL->get('prevalue') eq '0'))}
													&nbsp;{vtranslate('LBL_FROM')} <strong style="white-space:pre-wrap;">
													{vtranslate(Vtiger_Util_Helper::toVtiger6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('prevalue')))),$MODULE_NAME)}</strong>
												{else if $FIELDMODEL->get('postvalue') eq '' || ($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
													&nbsp; <strong> {vtranslate('LBL_DELETED')} </strong> ( <del>{Vtiger_Util_Helper::toVtiger6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('prevalue'))))}</del> )
												{else}
													&nbsp;{vtranslate('LBL_CHANGED')}
												{/if}
												{if $FIELDMODEL->get('postvalue') neq '' && !($FIELDMODEL->getFieldInstance()->getFieldDataType() eq 'reference' && $FIELDMODEL->get('postvalue') eq '0')}
													&nbsp;{vtranslate('LBL_TO')}&nbsp;<strong style="white-space:pre-wrap;">
													{vtranslate(Vtiger_Util_Helper::toVtiger6SafeHTML($FIELDMODEL->getDisplayValue(decode_html($FIELDMODEL->get('postvalue')))),$MODULE_NAME)}</strong>
												{/if}
	
										</div>
									{/if}
								{/foreach}
	
							</li>
						{else if ($RECENT_ACTIVITY->isRelationLink() || $RECENT_ACTIVITY->isRelationUnLink())}
							<li>
								<div>
									{assign var=RELATION value=$RECENT_ACTIVITY->getRelationInstance()}
									<span>{vtranslate($RELATION->getLinkedRecord()->getModuleName(), $RELATION->getLinkedRecord()->getModuleName())}</span> <span>
											{if $RECENT_ACTIVITY->isRelationLink()}
												{vtranslate('LBL_ADDED', $MODULE_NAME)}
											{else}
												{vtranslate('LBL_REMOVED', $MODULE_NAME)}
											{/if} </span><span>
											{if $RELATION->getLinkedRecord()->getModuleName() eq 'Calendar'}
												{if isPermitted('Calendar', 'DetailView', $RELATION->getLinkedRecord()->getId()) eq 'yes'} <strong>{$RELATION->getLinkedRecord()->getName()}</strong> {else} {/if}
											{else} <strong>{$RELATION->getLinkedRecord()->getName()}</strong> {/if}</span>
									<span class="pull-right"><p class="muted no-margin"><small title="{Vtiger_Util_Helper::formatDateTimeIntoDayString($RELATION->get('changedon'))}">{Vtiger_Util_Helper::formatDateDiffInStrings($RELATION->get('changedon'))}</small></p></span>
								</div>
							</li>
						{else if $RECENT_ACTIVITY->isRestore()}
							<li>
	
							</li>
						{else if $RECENT_ACTIVITY->isConvertToAccount()}
							<li>
								<strong>{vtranslate('LBL_CONVERTED_FROM_LEAD', $MODULE_NAME)}</strong> 
							</li>
						{/if}
					{/if}
				{/foreach}
			</ul>
			{else}
				<div class="summaryWidgetContainer">
					<p class="textAlignCenter">{vtranslate('LBL_NO_RECENT_UPDATES')}</p>
				</div>
		{/if}
	</div>
		<div class="row" id="moreLink">
		    {if $PAGING_MODEL->isNextPageExists()}
			<div class="pull-right">
				<a href="javascript:void(0)" class="moreRecentUpdates">{vtranslate('LBL_MORE',$MODULE_NAME)}..</a>
			</div>
		    {/if}
		</div>
	<span class="clearfix"></span>
</div>
{/strip}
