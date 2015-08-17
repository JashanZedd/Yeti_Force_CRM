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
    <div class="detailViewInfo" id="groupsDetailContainer" style="box-shadow:0;margin-top: 0;min-height:500px;">
		<div class="">
			<form id="detailView" class="form-horizontal" style="padding-top: 20px;" method="POST">
				<div class="row">
					<h3 class="col-md-6 settingsHeader">
						{$RECORD_MODEL->get('groupname')}
					</h3>
					<div class="col-md-6">
						<div class="pull-right">
							<button class="btn btn-info" onclick="window.location.href = '{$RECORD_MODEL->getEditViewUrl()}'" type="button">
								<strong>{vtranslate('LBL_EDIT_RECORD', $MODULE)}</strong>
							</button>
						</div>
					</div>
				</div><hr>
				<div class="form-group">
					<div class="col-md-2 control-label">
						{vtranslate('LBL_GROUP_NAME', $QUALIFIED_MODULE)} <span class="redColor">*</span>
					</div>
					<div class="controls pushDown">
						<strong>{$RECORD_MODEL->getName()}</strong>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 control-label">
						{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}
					</div>
					<div class="controls pushDown">
						<strong>{$RECORD_MODEL->getDescription()}</strong>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 control-label">
						{vtranslate('LBL_MODULES', $QUALIFIED_MODULE)}
					</div>
					<div class="controls pushDown">
						<div class="row">
							<div class="col-md-9 paddingLRZero">
								{foreach key=TABID item=MODULE from=$RECORD_MODEL->getModules() name=modules}
									{if  $smarty.foreach.modules.last}
										<strong>{vtranslate($MODULE,$MODULE)} </strong>
									{else}
										<strong>{vtranslate($MODULE,$MODULE)}, </strong>
									{/if} 
								{/foreach}
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-2 control-label">
						{vtranslate('LBL_GROUP_MEMBERS', $QUALIFIED_MODULE)}
					</div>
					<div class="col-md-5 controls pushDown">
						<div class="row">
							<div class="collectiveGroupMembers">
								<ul class="nav list-group">
									{assign var="GROUPS" value=$RECORD_MODEL->getMembers()}
									{foreach key=GROUP_LABEL item=GROUP_MEMBERS from=$GROUPS}
										{if !empty($GROUP_MEMBERS)}
											<li class="row groupLabel nav-header">
												{vtranslate($GROUP_LABEL,$QUALIFIED_MODULE)}
											</li>
											{foreach item=GROUP_MEMBER_INFO from=$GROUP_MEMBERS}
												<li class="">
													<a href="{$GROUP_MEMBER_INFO->getDetailViewUrl()}">{$GROUP_MEMBER_INFO->get('name')}</a>
												</li>
											{/foreach}
										{/if}
									{/foreach}
								</ul>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
{strip}
