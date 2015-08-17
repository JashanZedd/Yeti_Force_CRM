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
    {if !empty($CUSTOM_VIEWS)}
        <div class="relatedContainer listViewPageDiv margin0px">
            <input type="hidden" name="emailEnabledModules" value=true />
            <input type="hidden" id="view" value="{$VIEW}" />
            <input type="hidden" name="currentPageNum" value="{$PAGING->getCurrentPage()}" />
            <input type="hidden" name="relatedModuleName" class="relatedModuleName" value="{$RELATED_MODULE->get('name')}" />
            <input type="hidden" value="{$ORDER_BY}" id="orderBy">
            <input type="hidden" value="{$SORT_ORDER}" id="sortOrder">
            <input type="hidden" value="{$RELATED_ENTIRES_COUNT}" id="noOfEntries">
            <input type='hidden' value="{$PAGING->getPageLimit()}" id='pageLimit'>
            <input type="hidden" id="recordsCount" value=""/>
            <input type="hidden" id="selectedIds" name="selectedIds" data-selected-ids={ZEND_JSON::encode($SELECTED_IDS)} />
            <input type="hidden" id="excludedIds" name="excludedIds" data-excluded-ids={ZEND_JSON::encode($EXCLUDED_IDS)} />
            <input type="hidden" id="recordsCount" name="recordsCount" />
            <input type='hidden' value="{$TOTAL_ENTRIES}" id='totalCount'>
            <div class="relatedHeader">
                <div class="btn-toolbar row">
                    <div class="col-md-6">
                        {foreach item=RELATED_LINK from=$RELATED_LIST_LINKS['LISTVIEWBASIC']}
                            <div class="btn-group">
                                {assign var=IS_SELECT_BUTTON value={$RELATED_LINK->get('_selectRelation')}}
                                {assign var=IS_SEND_EMAIL_BUTTON value={$RELATED_LINK->get('_sendEmail')}}
                                <button type="button" class="btn btn-default addButton
										{if $IS_SELECT_BUTTON eq true} selectRelation {/if} moduleColor_{$RELATED_MODULE->get('name')} {if $RELATED_LINK->linkqcs eq true}quickCreateSupported{/if}"
										{if $IS_SELECT_BUTTON eq true} data-moduleName='{$RELATED_LINK->get('_module')->get('name')}' {/if}
										{if $RELATION_FIELD} data-name="{$RELATION_FIELD->getName()}" {/if}
										{if $IS_SEND_EMAIL_BUTTON eq true}	onclick="{$RELATED_LINK->getUrl()}" {else} data-url="{$RELATED_LINK->getUrl()}"{/if}
										{if ($IS_SELECT_BUTTON eq false) and ($IS_SEND_EMAIL_BUTTON eq false)}
											name="addButton"><span class="glyphicon glyphicon-plus"></span>
										{else}
											> {* closing the button tag *}
										{/if}&nbsp;<strong>{$RELATED_LINK->getLabel()}</strong>
								</button>
							</div>
						{/foreach}
						&nbsp;
					</div>
					<div class="col-md-3">
						<span class="customFilterMainSpan">
							{if $CUSTOM_VIEWS|@count gt 0}
								<select id="recordsFilter" class="col-md-12" data-placeholder="{vtranslate('LBL_SELECT_TO_LOAD_LIST', $RELATED_MODULE_NAME)}">
									<option></option>
									{foreach key=GROUP_LABEL item=GROUP_CUSTOM_VIEWS from=$CUSTOM_VIEWS}
										<optgroup label=' {if $GROUP_LABEL eq 'Mine'} &nbsp; {else if} {vtranslate($GROUP_LABEL)} {/if}' >
											{foreach item="CUSTOM_VIEW" from=$GROUP_CUSTOM_VIEWS}
												<option id="filterOptionId_{$CUSTOM_VIEW->get('cvid')}" value="{$CUSTOM_VIEW->get('cvid')}" class="filterOptionId_{$CUSTOM_VIEW->get('cvid')}" data-id="{$CUSTOM_VIEW->get('cvid')}">{if $CUSTOM_VIEW->get('viewname') eq 'All'}{vtranslate($CUSTOM_VIEW->get('viewname'), $RELATED_MODULE_NAME)} {vtranslate($RELATED_MODULE_NAME, $RELATED_MODULE_NAME)}{else}{vtranslate($CUSTOM_VIEW->get('viewname'), $RELATED_MODULE_NAME)}{/if}{if $GROUP_LABEL neq 'Mine'} [ {$CUSTOM_VIEW->getOwnerName()} ] {/if}</option>
											{/foreach}
										</optgroup>
									{/foreach}
								</select>
								<img class="filterImage" src="{'filter.png'|vimage_path}" style="display:none;height:13px;margin-right:2px;vertical-align: middle;">
							{else}
								<input type="hidden" value="0" id="customFilter" />
							{/if}
						</span>
					</div>
					<div class="col-md-3">
						<div class="pull-right">
							<span class="pageNumbers">
								<span class="pageNumbersText">{if !empty($RELATED_RECORDS)} {$PAGING->getRecordStartRange()} {vtranslate('LBL_to', $RELATED_MODULE->get('name'))} {$PAGING->getRecordEndRange()}{else}<span>&nbsp;</span>{/if}</span>
								<span class="glyphicon glyphicon-refresh cursorPointer totalNumberOfRecords{if empty($RELATED_RECORDS)} hide{/if}"></span>
							</span>
							<span class="btn-group">
								<button class="btn btn-default" id="relatedListPreviousPageButton" {if !$PAGING->isPrevPageExists()} disabled {/if} type="button"><span class="glyphicon glyphicon-chevron-left"></span></button>
								<button class="btn btn-default dropdown-toggle" type="button" id="relatedListPageJump" data-toggle="dropdown" {if $PAGE_COUNT eq 1} disabled {/if}>
									<span class="vtGlyph vticon-pageJump" title="{vtranslate('LBL_LISTVIEW_PAGE_JUMP',$moduleName)}"></span>
								</button>
								<ul class="listViewBasicAction dropdown-menu" id="relatedListPageJumpDropDown">
									<li>
										<div class="">
											<div class="col-md-4 recentComments textAlignCenter pushUpandDown2per"><span>{vtranslate('LBL_PAGE',$moduleName)}</span></div>
											<div class="col-md-3 recentComments">
												<input type="text" id="pageToJump" class="listViewPagingInput textAlignCenter" value="{$PAGING->getCurrentPage()}"/>
											</div>
											<div class="col-md-2 recentComments textAlignCenter pushUpandDown2per">
												{vtranslate('LBL_OF',$moduleName)}
											</div>
											<div class="col-md-2 recentComments textAlignCenter pushUpandDown2per" id="totalPageCount">{$PAGE_COUNT}</div>
										</div>
									</li>
								</ul>
								<button class="btn btn-default" id="relatedListNextPageButton" {if (!$PAGING->isNextPageExists()) or ($PAGE_COUNT eq 1)} disabled {/if} type="button"><span class="glyphicon glyphicon-chevron-right"></span></button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div id="selectAllMsgDiv" class="alert-block msgDiv">
				<strong><a id="selectAllMsg">{vtranslate('LBL_SELECT_ALL',$MODULE)}&nbsp;{vtranslate($RELATED_MODULE->get('name'))}&nbsp;(<span id="totalRecordsCount"></span>)</a></strong>
			</div>
			<div id="deSelectAllMsgDiv" class="alert-block msgDiv">
				<strong><a id="deSelectAllMsg">{vtranslate('LBL_DESELECT_ALL_RECORDS',$MODULE)}</a></strong>
			</div>
			<div class="contents-topscroll">
				<div class="topscroll-div">
					&nbsp;
				</div>
			</div>
			<div class="relatedContents contents-bottomscroll">
				<div class="bottomscroll-div">
					{assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
					<table class="table table-bordered listViewEntriesTable">
						<thead>
							<tr class="listViewHeaders">
								<th width="4%">
									<input type="checkbox" title="{vtranslate('LBL_SELECT_ALL')}" id="listViewEntriesMainCheckBox"/>
								</th>
								{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
									<th nowrap>
										{if $HEADER_FIELD->get('column') eq 'access_count' or $HEADER_FIELD->get('column') eq 'idlists' }
											<a href="javascript:void(0);" class="noSorting">{vtranslate($HEADER_FIELD->get('label'), $RELATED_MODULE->get('name'))}</a>
										{elseif $HEADER_FIELD->get('column') eq 'time_start'}
										{else}
											<a href="javascript:void(0);" class="relatedListHeaderValues" data-nextsortorderval="{if $COLUMN_NAME eq $HEADER_FIELD->get('column')}{$NEXT_SORT_ORDER}{else}ASC{/if}" data-fieldname="{$HEADER_FIELD->get('column')}">{vtranslate($HEADER_FIELD->get('label'), $RELATED_MODULE->get('name'))}
												&nbsp;&nbsp;{if $COLUMN_NAME eq $HEADER_FIELD->get('column')}<span class="{$SORT_IMAGE}"></span>{/if}
											</a>
										{/if}
									</th>
								{/foreach}
								<th nowrap colspan="2">
									<a href="javascript:void(0);" class="noSorting">{vtranslate('Status', $RELATED_MODULE->get('name'))}</a>
								</th>
							</tr>
						</thead>
						{foreach item=RELATED_RECORD from=$RELATED_RECORDS}
							<tr class="listViewEntries" data-id='{$RELATED_RECORD->getId()}' data-recordUrl='{$RELATED_RECORD->getDetailViewUrl()}'>
								<td width="4%" class="{$WIDTHTYPE}">
									<input type="checkbox" value="{$RELATED_RECORD->getId()}" title="{vtranslate('LBL_SELECT_SINGLE_ROW')}" class="listViewEntriesCheckBox"/>
								</td>
								{foreach item=HEADER_FIELD from=$RELATED_HEADERS}
									{assign var=RELATED_HEADERNAME value=$HEADER_FIELD->get('name')}
									<td nowrap class="{$WIDTHTYPE}">
										{if $HEADER_FIELD->isNameField() eq true or $HEADER_FIELD->get('uitype') eq '4'}
											<a href="{$RELATED_RECORD->getDetailViewUrl()}">{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}</a>
										{elseif $RELATED_HEADERNAME eq 'access_count'}
											{$RELATED_RECORD->getAccessCountValue($PARENT_RECORD->getId())}
										{elseif $RELATED_HEADERNAME eq 'time_start'}
										{else}
											{$RELATED_RECORD->getDisplayValue($RELATED_HEADERNAME)}
										{/if}
									</td>
								{/foreach}
								<td nowrap class="{$WIDTHTYPE}">
									<span class="currentStatus btn-group">
										<span class="statusValue dropdown-toggle" data-toggle="dropdown">{vtranslate($RELATED_RECORD->get('status'),$MODULE)}</span>
										<span title="{vtranslate('LBL_EDIT', $MODULE)}" class="icon-arrow-down alignMiddle editRelatedStatus"></span>
										<ul class="dropdown-menu pull-right" style="left: -2px; position: relative;">
											{foreach key=STATUS_ID item=STATUS from=$STATUS_VALUES}
												<li id="{$STATUS_ID}" data-status="{vtranslate($STATUS, $MODULE)}">
													<a>{vtranslate($STATUS, $MODULE)}</a>
												</li>
											{/foreach}
										</ul>
									</span>
								</td>
								<td nowrap class="{$WIDTHTYPE}">
									<div class="pull-right actions">
										<span class="actionImages">
											<a href="{$RELATED_RECORD->getFullDetailViewUrl()}"><span title="{vtranslate('LBL_SHOW_COMPLETE_DETAILS', $MODULE)}" class="glyphicon glyphicon-th-list alignMiddle"></span></a>&nbsp;
												{if $IS_EDITABLE}
												<a href='{$RELATED_RECORD->getEditViewUrl()}'><span title="{vtranslate('LBL_EDIT', $MODULE)}" class="glyphicon glyphicon-pencil alignMiddle"></span></a>
												{/if}
												{if $IS_DELETABLE}
												<a class="relationDelete"><span title="{vtranslate('LBL_DELETE', $MODULE)}" class="glyphicon glyphicon-trash alignMiddle"></span></a>
												{/if}
										</span>
									</div>
								</td>
							</tr>
						{/foreach}
					</table>
				</div>
			</div>
		</div>
	{else}
		{include file='RelatedList.tpl'|@vtemplate_path}
	{/if}
{/strip}
