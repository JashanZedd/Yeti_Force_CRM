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
	<div class="modelContainer modal fade quickCreateContainer" tabindex="-1">
		<div class="modal-dialog modal-lg modal-full">
			<div class="modal-content">
				<form class="form-horizontal recordEditView" name="QuickCreate" method="post" action="index.php">
					<div class="modal-header d-flex justify-content-between pb-1">
						<div>
							<h5 class="modal-title">
								<span class="fas fa-plus mr-1"></span>
								{\App\Language::translate('LBL_QUICK_CREATE', $MODULE)}:
								<span class="userIcon-{$MODULE} mx-1"></span>
								<p class="textTransform"><strong>{\App\Language::translate($SINGLE_MODULE, $MODULE)}</strong></p>
							</h5>
						</div>
						<div>
							{assign var="EDIT_VIEW_URL" value=$MODULE_MODEL->getCreateRecordUrl()}
							{foreach item=LINK from=$QUICKCREATE_LINKS['QUICKCREATE_VIEW_HEADER']}
								{include file=\App\Layout::getTemplatePath('ButtonLink.tpl', $MODULE) BUTTON_VIEW='quickcreateViewHeader'}
							{/foreach}
							<button class="btn btn-outline-secondary goToFullFormOne" id="goToFullForm" data-edit-view-url="{$EDIT_VIEW_URL}" type="button"><strong>{\App\Language::translate('LBL_GO_TO_FULL_FORM', $MODULE)}</strong></button>&nbsp;
							<button class="btn btn-success" type="submit" title="{\App\Language::translate('LBL_SAVE', $MODULE)}"><strong><span class="fas fa-check"></span></strong></button>
							<button class="cancelLink text-white btn btn-danger" aria-hidden="true" data-dismiss="modal" type="button" title="{\App\Language::translate('LBL_CLOSE')}"><span class="fas fa-times"></span></button>
						</div>
					</div>
					{if !empty($PICKIST_DEPENDENCY_DATASOURCE)}
						<input type="hidden" name="picklistDependency" value='{\App\Purifier::encodeHtml($PICKIST_DEPENDENCY_DATASOURCE)}' />
					{/if}
					{if !empty($MAPPING_RELATED_FIELD)}
						<input type="hidden" name="mappingRelatedField" value='{\App\Purifier::encodeHtml($MAPPING_RELATED_FIELD)}' />
					{/if}
					<input type="hidden" name="module" value="{$MODULE}" />
					<input type="hidden" name="action" value="SaveAjax" />
					<div class="quickCreateContent">
						<div class="modal-body m-0">
							<div class="massEditTable border-0 px-1 mx-auto m-0">
								<div class="px-0 form-row align-items-start mx-auto">
									{assign var=COUNTER value=0}
									{foreach key=FIELD_NAME item=FIELD_MODEL from=$RECORD_STRUCTURE name=blockfields}
										{if $COUNTER eq 2}
										</div>
										<div class="col-12 form-row align-items-start px-0 m-auto">
											{assign var=COUNTER value=1}
										{else}
											{assign var=COUNTER value=$COUNTER+1}
										{/if}
										<div class="col-md-6 py-2 form-row align-items-center {$WIDTHTYPE} ">
											<div class="fieldLabel col-sm-3 pl-0">
												{assign var=HELPINFO value=explode(',',$FIELD_MODEL->get('helpinfo'))}
												{assign var=HELPINFO_LABEL value=$MODULE|cat:'|'|cat:$FIELD_MODEL->getFieldLabel()}

												<label class="text-right muted small font-weight-bold float-sm-left float-sm-right float-lg-right">
													{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span>{/if}
													{if in_array($VIEW,$HELPINFO) && \App\Language::translate($HELPINFO_LABEL, 'HelpInfo') neq $HELPINFO_LABEL}
														<a href="#" class="js-help-info float-right" title="" data-placement="top" data-content="{\App\Language::translate($HELPINFO_LABEL, 'HelpInfo')}" data-original-title='{\App\Language::translate($FIELD_MODEL->getFieldLabel(), $MODULE)}'><span class="fas fa-info-circle"></span></a>{/if}
															{\App\Language::translate($FIELD_MODEL->getFieldLabel(), $MODULE)}
													</label>
												</div>
												<div class="fieldValue col-sm-9" >
													{include file=\App\Layout::getTemplatePath($FIELD_MODEL->getUITypeModel()->getTemplateName(), $MODULE)}
												</div>
											</div>
											{/foreach}
												{if $COUNTER eq 1}
													<div class="col-md-6 form-row align-items-center p-1 {$WIDTHTYPE} px-0"></div>
												{/if}
											</div>
										</div>
									</div>
								</div>
								{if !empty($SOURCE_RELATED_FIELD)}
									{foreach key=RELATED_FIELD_NAME item=RELATED_FIELD_MODEL from=$SOURCE_RELATED_FIELD}
										<input type="hidden" name="{$RELATED_FIELD_NAME}" value="{\App\Purifier::encodeHtml($RELATED_FIELD_MODEL->get('fieldvalue'))}" data-fieldtype="{$RELATED_FIELD_MODEL->getFieldDataType()}" />
									{/foreach}
								{/if}
							</form>
						</div>
					</div>
				</div>
				{/strip}
