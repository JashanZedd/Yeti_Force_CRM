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
	{foreach key=index item=jsModel from=$SCRIPTS}
		<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
	{/foreach}
	{assign var=WIDTHTYPE value=$USER_MODEL->get('rowheight')}
	<div class="tpl-QuickCreate modal quickCreateContainer" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-lg modal-full" role="document">
			<div class="modal-content">
				<form class="form-horizontal recordEditView" name="QuickCreate" method="post" action="index.php">
					<div class="modal-header col-12 m-0 align-items-center form-row d-flex justify-content-between py-2">
						<div class="col-xl-6 col-12">
							<h5 class="modal-title form-row text-center text-xl-left mb-2 mb-xl-0">
								<span class="col-12">
									<span class="fas fa-plus mr-1"></span>
									<strong class="mr-1">{\App\Language::translate('LBL_QUICK_CREATE', $MODULE)}
										:</strong>
									<strong class="text-uppercase"><span
												class="userIcon-{$MODULE} mx-1"></span>{\App\Language::translate($SINGLE_MODULE, $MODULE)}</strong>
								</span>
							</h5>
						</div>
						<div class="col-xl-6 col-12 text-center text-xl-right">
							{assign var="EDIT_VIEW_URL" value=$MODULE_MODEL->getCreateRecordUrl()}
							{if !empty($QUICKCREATE_LINKS['QUICKCREATE_VIEW_HEADER'])}
								{foreach item=LINK from=$QUICKCREATE_LINKS['QUICKCREATE_VIEW_HEADER']}
									{include file=\App\Layout::getTemplatePath('ButtonLink.tpl', $MODULE) BUTTON_VIEW='quickcreateViewHeader' CLASS='display-block-md'}
								{/foreach}
							{/if}
							<button class="btn btn-success mr-1" type="submit"
									title="{\App\Language::translate('LBL_SAVE', $MODULE)}">
								<strong><span class="fas fa-check"></span></strong>
							</button>
							<button class="cancelLink btn btn-danger"
									data-dismiss="modal" type="button" title="{\App\Language::translate('LBL_CLOSE')}">
								<span class="fas fa-times"></span>
							</button>
						</div>
					</div>
					{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
						<input type="hidden" name="picklistDependency"
							   value='{\App\Purifier::encodeHtml($PICKIST_DEPENDENCY_DATASOURCE)}'/>
					{/if}
					{if !empty($MAPPING_RELATED_FIELD)}
						<input type="hidden" name="mappingRelatedField"
							   value='{\App\Purifier::encodeHtml($MAPPING_RELATED_FIELD)}'/>
					{/if}
					<input type="hidden" name="module" value="{$MODULE}"/>
					<input type="hidden" name="action" value="SaveAjax"/>
					<div class="quickCreateContent">
						<div class="modal-body m-0">
							{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$RECORD_STRUCTURE}
								{if $BLOCK_FIELDS|@count lte 0}{continue}{/if}
								{assign var=BLOCK value=$BLOCK_LIST[$BLOCK_LABEL]}
								{assign var=IS_HIDDEN value=$BLOCK->isHidden()}
								{assign var=IS_DYNAMIC value=$BLOCK->isDynamic()}
								<div class="js-toggle-panel c-panel c-panel--edit mb-3"
									data-js="click|data-dynamic" {if $IS_DYNAMIC} data-dynamic="true"{/if}
									data-label="{$BLOCK_LABEL}">
									<div class="blockHeader c-panel__header align-items-center">
										{if $BLOCK_LABEL eq 'LBL_ADDRESS_INFORMATION' || $BLOCK_LABEL eq 'LBL_ADDRESS_MAILING_INFORMATION' || $BLOCK_LABEL eq 'LBL_ADDRESS_DELIVERY_INFORMATION'}
											{assign var=SEARCH_ADDRESS value=TRUE}
										{else}
											{assign var=SEARCH_ADDRESS value=FALSE}
										{/if}
										<span class="u-cursor-pointer js-block-toggle fas fa-angle-right m-2 {if !($IS_HIDDEN)}d-none{/if}"
												data-js="click" data-mode="hide"
												data-id={$BLOCK_LIST[$BLOCK_LABEL]->get('id')}></span>
										<span class="u-cursor-pointer js-block-toggle fas fa-angle-down m-2 {if ($IS_HIDDEN)}d-none{/if}"
												data-js="click" data-mode="show"
												data-id={$BLOCK_LIST[$BLOCK_LABEL]->get('id')}></span>
										<h5>{\App\Language::translate($BLOCK_LABEL, $MODULE_NAME)}</h5>
									</div>
									<div class="c-panel__body c-panel__body--edit blockContent js-block-content {if $IS_HIDDEN}d-none{/if}"
										data-js="display">
										{if $BLOCK_LABEL eq 'LBL_ADDRESS_INFORMATION' || $BLOCK_LABEL eq 'LBL_ADDRESS_MAILING_INFORMATION' || $BLOCK_LABEL eq 'LBL_ADDRESS_DELIVERY_INFORMATION'}
											<div class="{if !$SEARCH_ADDRESS} {/if} adressAction row py-2 justify-content-center">
												{include file=\App\Layout::getTemplatePath('BlockHeader.tpl', $MODULE)}
											</div>
										{/if}
										<div class="row">
											{assign var=COUNTER value=0}
											{foreach key=FIELD_NAME item=FIELD_MODEL from=$BLOCK_FIELDS name=blockfields}
											{if ($FIELD_NAME === 'time_start' || $FIELD_NAME === 'time_end') && ($MODULE === 'OSSTimeControl' || $MODULE === 'Reservations')}{continue}{/if}
											{if $FIELD_MODEL->getUIType() eq '20' || $FIELD_MODEL->getUIType() eq '300'}
											{if $COUNTER eq '1'}
										</div>
										<div class="row">
											{assign var=COUNTER value=0}
											{/if}
											{/if}
											{if $COUNTER eq 2}
										</div>
										<div class="row">
											{assign var=COUNTER value=1}
											{else}
											{assign var=COUNTER value=$COUNTER+1}
											{/if}
											{if isset($RECORD_STRUCTURE_RIGHT)}
											<div class="col-sm-12  row form-group align-items-center my-1">
												{else}
												<div class="{if $FIELD_MODEL->get('label') eq "FL_REAPEAT"} col-sm-3
											{elseif $FIELD_MODEL->get('label') eq "FL_RECURRENCE"} col-sm-9
											{elseif $FIELD_MODEL->getUIType() neq "300"}col-sm-6
											{else} col-md-12 m-auto{/if}  row form-group align-items-center my-1">
													{/if}
														{assign var=HELPINFO_LABEL value=\App\Language::getTranslateHelpInfo($FIELD_MODEL, $VIEW)}
													<label class="my-0 col-lg-12 col-xl-3 fieldLabel text-lg-left text-xl-right u-text-small-bold">
														{if $FIELD_MODEL->isMandatory() eq true}
															<span class="redColor">*</span>
														{/if}
														{if $HELPINFO_LABEL}
															<a href="#" class="js-help-info float-right u-cursor-pointer"
																title=""
																data-placement="top"
																data-content="{$HELPINFO_LABEL}"
																data-original-title="{\App\Language::translate($FIELD_MODEL->getFieldLabel(), $FIELD_MODEL->getModuleName())}">
																<span class="fas fa-info-circle"></span>
															</a>
														{/if}
														{\App\Language::translate($FIELD_MODEL->getFieldLabel(), $MODULE_NAME)}
													</label>
													<div class="{$WIDTHTYPE} w-100 {if $FIELD_MODEL->getUIType() neq "300"} col-lg-12 col-xl-9 {/if} fieldValue" {if $FIELD_MODEL->getUIType() eq '20'} colspan="3" {assign var=COUNTER value=$COUNTER+1}{elseif $FIELD_MODEL->getUIType() eq '300'} colspan="4" {assign var=COUNTER value=$COUNTER+1} {/if}>
														{if $FIELD_MODEL->getUIType() eq "300"}
															<label class="u-text-small-bold">{if $FIELD_MODEL->isMandatory() eq true}
																	<span class="redColor">*</span>
																{/if}{\App\Language::translate($FIELD_MODEL->getFieldLabel(), $MODULE)}
															</label>
														{/if}
														{include file=\App\Layout::getTemplatePath($FIELD_MODEL->getUITypeModel()->getTemplateName(), $MODULE) BLOCK_FIELDS=$BLOCK_FIELDS}
													</div>
												</div>
												{/foreach}
											</div>
										</div>
									</div>
						{/foreach}
					</div>
						</div>
					</div>
					{if !empty($SOURCE_RELATED_FIELD)}
						{foreach key=RELATED_FIELD_NAME item=RELATED_FIELD_MODEL from=$SOURCE_RELATED_FIELD}
							<input type="hidden" name="{$RELATED_FIELD_NAME}"
								   value="{\App\Purifier::encodeHtml($RELATED_FIELD_MODEL->get('fieldvalue'))}"
								   data-fieldtype="{$RELATED_FIELD_MODEL->getFieldDataType()}"/>
						{/foreach}
					{/if}
				</form>
			</div>
		</div>
	</div>
{/strip}
