{*<!--
/* {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} */
-->*}
{strip}
	{assign var=LOCKEDIT value=Users_Privileges_Model::checkLockEdit($RELATED_MODULE_NAME, $RELATED_RECORD->getId())}
	{assign var=OPENRECURRINGORDERS value=Users_Privileges_Model::isPermitted($RELATED_MODULE_NAME, 'OpenRecord', $RELATED_RECORD->getId())}
	<div class="pull-right actions">
		<span class="actionImages">
			{if ($IS_EDITABLE && $LOCKEDIT eq false) || $OPENRECURRINGORDERS}
				<a class="showModal" data-url="{$RELATED_RECORD->getModalUrl()}"><span title="{vtranslate('LBL_SET_RECORD_STATUS', $MODULE)}" class="glyphicon glyphicon-modal-window alignMiddle"></span></a>&nbsp;
			{/if}
			{if $DETAILVIEWPERMITTED eq 'yes'}
			<a href="{$RELATED_RECORD->getFullDetailViewUrl()}"><span title="{vtranslate('LBL_SHOW_COMPLETE_DETAILS', $MODULE)}" class="glyphicon glyphicon-th-list alignMiddle"></span></a>&nbsp;
			{/if}
			{if $IS_EDITABLE && $LOCKEDIT eq false}
				<a href='{$RELATED_RECORD->getEditViewUrl()}'><span title="{vtranslate('LBL_EDIT', $MODULE)}" class="glyphicon glyphicon-pencil alignMiddle"></span></a>
			{/if}
			{if $IS_DELETABLE}
				<a class="relationDelete"><span title="{vtranslate('LBL_DELETE', $MODULE)}" class="glyphicon glyphicon-trash alignMiddle"></span></a>
			{/if}
		</span>
	</div>
{/strip}

