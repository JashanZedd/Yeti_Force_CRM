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
	<!-- tpl-ShowAllComments -->
	{* Change to this also refer: RecentComments.tpl *}
	{assign var="COMMENT_TEXTAREA_DEFAULT_ROWS" value="2"}
	<input type="hidden" id="currentComment" value="{if !empty($CURRENT_COMMENT)}{$CURRENT_COMMENT->getId()}{/if}">
	<div class="col-md-12 form-row m-0 commentsBar px-0">
		{if $COMMENTS_MODULE_MODEL->isPermitted('CreateView')}
			<div class="commentTitle col-12 pt-2">
				<div class="js-addCommentBlock addCommentBlock" data-js="container">
					<div class="input-group">
						<span class="input-group-prepend">
							<div class="input-group-text"><span class="fas fa-comments"></span></div>
						</span>
						<textarea rows="{$COMMENT_TEXTAREA_DEFAULT_ROWS}" name="commentcontent"
								  class="commentcontent form-control"
								  title="{\App\Language::translate('LBL_ADD_YOUR_COMMENT_HERE', $MODULE_NAME)}"
								  placeholder="{\App\Language::translate('LBL_ADD_YOUR_COMMENT_HERE', $MODULE_NAME)}"></textarea>
					</div>
					<button class="btn btn-success mt-3 js-saveComment saveComment float-right" type="button"
							data-mode="add"
							data-js="click|data-mode">
						<span class="visible-xs-inline-block fas fa-check"></span>
						<strong class="d-none d-sm-none d-md-inline ml-1">{\App\Language::translate('LBL_POST', $MODULE_NAME)}</strong>
					</button>
				</div>
			</div>
		{/if}
	</div>
	{if count($HIERARCHY_LIST) != 1}
		<div class="row">
			<div class="col-lg-6"></div>
			<div class="col-md-12 col-lg-6 form-row commentsHeader my-3">
				<div class="col-6 col-lg-6 col-md-6 col-sm-6 p-0">
					<div class="input-group-append bg-white rounded-right">
						<input type="text" class="js-commentSearch form-control commentSearch"
							   placeholder="{\App\Language::translate('LBL_COMMENTS_SEARCH','ModComments')}"
							   aria-describedby="commentSearchAddon"
							   data-js="keypress|data">
						<button class="btn btn-outline-dark border-0 h-100 js-searchIcon searchIcon" type="button"
								data-js="click">
							<span class="fas fa-search fa-fw" title="{\App\Language::translate('LBL_SEARCH')}"></span>
						</button>
					</div>
				</div>
				<div class="col-5 col-lg-6 col-md-6 col-sm-6 p-0 text-right m-md-0 m-lg-0"
					 data-toggle="buttons">
					<div class="btn-group btn-group-toggle detailCommentsHierarchy" data-toggle="buttons">
						<label class="btn btn-sm btn-outline-primary {if $HIERARCHY_VALUE !== 'all'}active{/if}">
							<input class="js-detailHierarchyComments detailHierarchyComments" type="radio"
								   name="options" id="option1"
								   value="current" autocomplete="off"
								   {if $HIERARCHY_VALUE !== 'all'}checked="checked"{/if}
								   data-js="change"
							> {\App\Language::translate('LBL_COMMENTS_0', 'ModComments')}
						</label>
						<label class="btn btn-sm btn-outline-primary {if $HIERARCHY_VALUE === 'all'}active{/if}">
							<input class="js-detailHierarchyComments detailHierarchyComments" type="radio"
								   name="options" id="option2" value="all"
								   {if $HIERARCHY_VALUE === 'all'}checked="checked"{/if}
								   data-js="change"
								   autocomplete="off"> {\App\Language::translate('LBL_ALL_RECORDS', 'ModComments')}
						</label>
					</div>
				</div>
			</div>
		</div>
	{/if}
	<div class="commentContainer">
		<div class="commentsList col-md-12 px-0">
			{include file=\App\Layout::getTemplatePath('CommentsList.tpl') COMMENT_MODULE_MODEL=$COMMENTS_MODULE_MODEL}
		</div>
	</div>
	<!-- /tpl-ShowAllComments -->
{/strip}
