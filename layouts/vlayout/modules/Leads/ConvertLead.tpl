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
<div id="convertLeadContainer" class='modelContainer modal fade' tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			{if !$CONVERT_LEAD_FIELDS['Accounts'] && !$CONVERT_LEAD_FIELDS['Contacts']}
				<input type="hidden" id="convertLeadErrorTitle" value="{vtranslate('LBL_CONVERT_LEAD_ERROR_TITLE',$MODULE)}"/>
				<input id="convertLeadError" class="convertLeadError" type="hidden" value="{vtranslate('LBL_CONVERT_LEAD_ERROR',$MODULE)}"/>
			{else}
				{assign var=CONVERSION_CONFIG value=Vtiger_Processes_Model::getConfig('marketing','conversion')}
				<div class="modal-header contentsBackground">
					<button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">&times;</button>
					<h3 class="modal-title">{vtranslate('LBL_CONVERT_LEAD', $MODULE)} : {$RECORD->getName()}</h3>
				</div>
				<form class="form-horizontal" id="convertLeadForm" method="post" action="index.php">
					<input type="hidden" name="module" value="{$MODULE}"/>
					<input type="hidden" name="view" value="SaveConvertLead"/>
					<input type="hidden" name="record" value="{$RECORD->getId()}"/>
					<input type="hidden" name="modules" value=''/>
					<input type="hidden" name="create_account" value="{if $CONVERSION_CONFIG['create_always'] eq 'true'}1{/if}" />
					<div class="modal-body accordion" id="leadAccordion">
						{foreach item=MODULE_FIELD_MODEL key=MODULE_NAME from=$CONVERT_LEAD_FIELDS}
							<div class="accordion-group convertLeadModules">
								<div class="header accordion-heading">
									<div data-parent="#leadAccordion" data-toggle="collapse" class="accordion-toggle table-bordered moduleSelection" href="#{$MODULE_NAME}_FieldInfo">
										{if $ACCOUNT_FIELD_MODEL->isMandatory()}
											<input type="hidden" id="oppAccMandatory" value={$ACCOUNT_FIELD_MODEL->isMandatory()} />
										{/if}
										{if $CONTACT_FIELD_MODEL && $CONTACT_FIELD_MODEL->isMandatory()}
											<input type="hidden" id="oppConMandatory" value={$CONTACT_FIELD_MODEL->isMandatory()} />
										{/if}
										{if $CONTACT_ACCOUNT_FIELD_MODEL->isMandatory()}
											<input type="hidden" id="conAccMandatory" value={$CONTACT_ACCOUNT_FIELD_MODEL->isMandatory()} />
										{/if}
										<input id="{$MODULE_NAME}Module" class="convertLeadModuleSelection alignBottom" data-module="{vtranslate($MODULE_NAME,$MODULE_NAME)}" value="{$MODULE_NAME}" type="checkbox" {if $MODULE_NAME == 'Accounts' && $CONTACT_ACCOUNT_FIELD_MODEL && $CONTACT_ACCOUNT_FIELD_MODEL->isMandatory()} disabled="disabled" {/if} checked="" />
											{assign var=SINGLE_MODULE_NAME value="SINGLE_$MODULE_NAME"}
											<span style="position:relative;top:2px;">&nbsp;&nbsp;&nbsp;{vtranslate('LBL_CREATE', $MODULE)}&nbsp;{vtranslate($SINGLE_MODULE_NAME, $MODULE_NAME)}</span>
											<span class="pull-right"><i class="iconArrow{if $CONVERT_LEAD_FIELDS['Accounts'] && $MODULE_NAME == "Accounts"} icon-inverted icon-chevron-up {elseif !$CONVERT_LEAD_FIELDS['Accounts'] && $MODULE_NAME == "Contacts"} icon-inverted glyphicon glyphicon-chevron-up {else} icon-inverted glyphicon glyphicon-chevron-down {/if}alignBottom"></i></span>
									</div>
								</div>
								<div id="{$MODULE_NAME}_FieldInfo" class="{$MODULE_NAME}_FieldInfo accordion-body collapse fieldInfo in">
									<table class="table table-bordered moduleBlock">
										{foreach item=FIELD_MODEL from=$MODULE_FIELD_MODEL}
										<tr>
											<td class="fieldLabel">
												<label class='muted pull-right marginRight10px'>
													{if $FIELD_MODEL->isMandatory() eq true} <span class="redColor">*</span> {/if} 
													{vtranslate($FIELD_MODEL->get('label'), $MODULE_NAME)}

												</label>
											</td>
											<td class="fieldValue">
													{include file=$FIELD_MODEL->getUITypeModel()->getTemplateName()|@vtemplate_path}
											</td>
										</tr>
										{/foreach}
									</table>
								</div>
							</div>
						{/foreach}
						<div class="overflowVisible">
							<table class="table table-bordered">
								{assign var=FIELD_MODEL value=$ASSIGN_TO}
								<tr>
									<td class="fieldLabel">
										<label class='muted pull-right'>
											<span class="redColor">*</span> {vtranslate($FIELD_MODEL->get('label'), $MODULE_NAME)}
											{if $FIELD_MODEL->isMandatory() eq true} {/if}
										</label>
									</td>
									<td class="fieldValue">
										{assign var="FIELD_INFO" value=Vtiger_Util_Helper::toSafeHTML(Zend_Json::encode($FIELD_MODEL->getFieldInfo()))}
										{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
										{if $FIELD_MODEL->get('uitype') eq '53'}
											{assign var=ALL_ACTIVEUSER_LIST value=$USER_MODEL->getAccessibleUsers()}
											{assign var=ALL_ACTIVEGROUP_LIST value=$USER_MODEL->getAccessibleGroups()}
											{assign var=ASSIGNED_USER_ID value=$FIELD_MODEL->get('name')}
											{assign var=CURRENT_USER_ID value=$USER_MODEL->get('id')}
											{assign var=FIELD_VALUE value=$FIELD_MODEL->get('fieldvalue')}

											{assign var=ACCESSIBLE_USER_LIST value=$USER_MODEL->getAccessibleUsersForModule($MODULE)}
											{assign var=ACCESSIBLE_GROUP_LIST value=$USER_MODEL->getAccessibleGroupForModule($MODULE)}

											{if $FIELD_VALUE eq '' || $CONVERSION_CONFIG['change_owner'] == 'true'}
												{assign var=FIELD_VALUE value=$CURRENT_USER_ID}
											{/if}
											<select class="chzn-select {$ASSIGNED_USER_ID} form-control" data-validation-engine="validate[{if $FIELD_MODEL->isMandatory() eq true} required,{/if}funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" data-name="{$ASSIGNED_USER_ID}" name="{$ASSIGNED_USER_ID}" data-fieldinfo='{$FIELD_INFO}' {if !empty($SPECIAL_VALIDATOR)}data-validator={Zend_Json::encode($SPECIAL_VALIDATOR)}{/if} {if $FIELD_MODEL->get('displaytype') == 10}readonly="readonly"{/if}>
												<optgroup label="{vtranslate('LBL_USERS')}">
													{foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEUSER_LIST}

															<option value="{$OWNER_ID}" data-picklistvalue= '{$OWNER_NAME}' 
																{if array_key_exists($OWNER_ID, $ACCESSIBLE_USER_LIST)} data-recordaccess=true {else} data-recordaccess=false {/if}
																	{if $FIELD_VALUE eq $OWNER_ID} selected {/if}
																data-userId="{$CURRENT_USER_ID}">
																{$OWNER_NAME}	
															</option>
													{/foreach}
												</optgroup>
												<optgroup label="{vtranslate('LBL_GROUPS')}">
													{foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEGROUP_LIST}
														<option value="{$OWNER_ID}" data-picklistvalue= '{$OWNER_NAME}' {if $FIELD_VALUE eq $OWNER_ID} selected {/if}
															{if array_key_exists($OWNER_ID, $ACCESSIBLE_GROUP_LIST)} data-recordaccess=true {else} data-recordaccess=false {/if} >
														{$OWNER_NAME}
														</option>
													{/foreach}
												</optgroup>
											</select>
										{/if}
									</td>
								</tr>
								<!--
								<tr>
									<td class="fieldLabel">
										<label class='muted pull-right'>
											{vtranslate('LBL_TRANSFER_RELATED_RECORD', $MODULE)}
										</label>
									</td>
									<td class="fieldValue">
										{foreach item=MODULE_FIELD_MODEL key=MODULE_NAME from=$CONVERT_LEAD_FIELDS}
											{if $MODULE_NAME != 'Potentials'}
												<input type="radio" id="transfer{$MODULE_NAME}" class="transferModule alignBottom" name="transferModule" value="{$MODULE_NAME}"
												{if $CONVERT_LEAD_FIELDS['Contacts'] && $MODULE_NAME=="Contacts"} checked="" {elseif !$CONVERT_LEAD_FIELDS['Contacts'] && $MODULE_NAME=="Accounts"} checked="" {/if}/>
												{if $MODULE_NAME eq 'Contacts'}
													&nbsp; {vtranslate('SINGLE_Contacts',$MODULE_NAME)} &nbsp;&nbsp;
												{else}
													&nbsp; {vtranslate('SINGLE_Accounts',$MODULE_NAME)} &nbsp;&nbsp;
												{/if}
											{/if}
										{/foreach}
									</td>
								</tr>
								-->
							</table>
						</div>
					</div>
					{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
				</form>
			{/if}
		</div>
	</div>
</div>
{/strip}
