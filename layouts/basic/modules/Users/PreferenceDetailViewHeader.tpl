{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): YetiForce Sp. z o.o.
********************************************************************************/
-->*}
{strip}
	{assign var="MODULE_NAME" value=$MODULE_MODEL->get('name')}
	<input id="recordId" type="hidden" value="{$RECORD->getId()}" />
	<div class="PreferenceDetailViewHeader detailViewContainer">
		<div class="detailViewTitle marginTop5" id="prefPageHeader">
			<div class="row">
				<div class="ml-0 pr-1 col-md-5 row">
					<div class="logo pl-0 col-2 col-md-2">
						{foreach key=ITER item=IMAGE_INFO from=$RECORD->getImageDetails()}
							{if !empty($IMAGE_INFO.path) && !empty($IMAGE_INFO.orgname)}
								<img src="data:image/jpg;base64,{base64_encode(file_get_contents($IMAGE_INFO.path))}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" data-image-id="{$IMAGE_INFO.id}">
							{/if}
						{/foreach}
					</div>
					<div class="col-10 col-md-10 p-0 d-flex flex-column">
						<div id="myPrefHeading">
							<h3>{\App\Language::translate('LBL_MY_PREFERENCES', $MODULE_NAME)} </h3>
						</div>
						<div>
							{\App\Language::translate('LBL_USERDETAIL_INFO', $MODULE_NAME)}&nbsp;&nbsp;"<strong>{$RECORD->getName()}</strong>"
						</div>
					</div>
				</div>
				<div class="mr-0 pl-1 col-md-7 py-3">
					<div class="row float-right detailViewButtoncontainer">
						<div class="btn-toolbar float-right">
							{foreach item=LINK from=$DETAILVIEW_LINKS['DETAILVIEWPREFERENCE']}
								{include file=\App\Layout::getTemplatePath('ButtonLink.tpl', $MODULE) BUTTON_VIEW='detailViewBasic'}
							{/foreach}
							{if $DETAILVIEW_LINKS['DETAIL_VIEW_BASIC']|@count gt 0}
								<span class="btn-group">
									<button class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
										<strong>{\App\Language::translate('LBL_MORE', $MODULE_NAME)}</strong>&nbsp;&nbsp;<i class="caret"></i>
									</button>
									<ul class="dropdown-menu">
										{foreach item=DETAIL_VIEW_LINK from=$DETAILVIEW_LINKS['DETAIL_VIEW_BASIC']}
											<li class="dropdown-item" id="{$MODULE_NAME}_detailView_moreAction_{Vtiger_Util_Helper::replaceSpaceWithUnderScores($DETAIL_VIEW_LINK->getLabel())}">
												<a href={$DETAIL_VIEW_LINK->getUrl()} >{\App\Language::translate($DETAIL_VIEW_LINK->getLabel(), $MODULE_NAME)}</a>
											</li>
										{/foreach}
									</ul>
								</span>
							{/if}
						</div>
					</div>
				</div>
			</div>
			<div class="detailViewInfo userPreferences row">
				<div class="details col-md-12">
					<form id="detailView" data-name-fields='{\App\Json::encode($MODULE_MODEL->getNameFields())}' method="POST">
						<div class="contents">
						{/strip}
