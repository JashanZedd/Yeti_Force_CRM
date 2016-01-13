{*<!--
/* {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} */
-->*}
{strip}
	{assign var=ID value=$RECORD->get('id')}
	{assign var=EDITVIEW_PERMITTED value=Users_Privileges_Model::isPermitted($MODULE_NAME, 'EditView', $ID)}
	{assign var=DETAILVIEW_PERMITTED value=Users_Privileges_Model::isPermitted($MODULE_NAME, 'DetailView', $ID)}
	{assign var=OPENRECURRINGORDERS value=Users_Privileges_Model::isPermitted($MODULE_NAME, 'OpenRecord', $ID)}
	{assign var=CLOSERECURRINGORDERS value=Users_Privileges_Model::isPermitted($MODULE_NAME, 'CloseRecord', $ID)}
	{assign var=LOCKEDIT value=Users_Privileges_Model::checkLockEdit($MODULE_NAME, $ID)}
	<div class="modal-header">
		<div class="pull-left">
			<h3 class="modal-title">{vtranslate('LBL_SET_RECORD_STATUS', $MODULE_NAME)}</h3>
		</div>
		<div class="pull-right btn-group">
			{if $EDITVIEW_PERMITTED && !$LOCKEDIT}
				<a href="{$RECORD->getEditViewUrl()}" class="btn btn-default" title="{vtranslate('LBL_EDIT',$MODULE_NAME)}"><span class="glyphicon glyphicon-pencil summaryViewEdit"></span></a>
			{/if}
			{if $DETAILVIEW_PERMITTED}
				<a href="{$RECORD->getDetailViewUrl()}" class="btn btn-default" title="{vtranslate('LBL_SHOW_COMPLETE_DETAILS', $MODULE_NAME)}"><span  class="glyphicon glyphicon-th-list summaryViewEdit"></span></a>
			{/if}
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="modal-body">
		{if $DETAILVIEW_PERMITTED}
			<div class="form-horizontal">
				{foreach key=BLOCK_LABEL_KEY item=FIELD_MODEL_LIST from=$RECORD_STRUCTURE}
					{if $FIELD_MODEL_LIST|@count lte 0}{continue}{/if}
					{foreach item=FIELD_MODEL key=FIELD_NAME from=$FIELD_MODEL_LIST}
						{if !$FIELD_MODEL->isViewableInDetailView()}
							{continue}
						{/if}
						{assign var=VALUE value={include file=vtemplate_path($FIELD_MODEL->getUITypeModel()->getDetailViewTemplateName(),$MODULE_NAME) FIELD_MODEL=$FIELD_MODEL USER_MODEL=$USER_MODEL MODULE=$MODULE_NAME RECORD=$RECORD}}
						<div class="form-group">
							<label class="col-sm-4 control-label">{vtranslate($FIELD_MODEL->get('label'),$MODULE_NAME)} 
								{if in_array($FIELD_MODEL->get('uitype'),['300','19']) && $VALUE}
									<a href="#" class="helpInfoPopover" title="{vtranslate('LBL_PREVIEW',$MODULE_NAME)}" data-placement="auto right" data-content="{htmlspecialchars($VALUE)}"> <span title="{vtranslate('LBL_PREVIEW',$MODULE_NAME)}" class="glyphicon glyphicon-modal-window"></span> </a>
									{assign var=CONVERT value=true}
								{/if}
							:</label>
							<div class="col-sm-8 textOverflowEllipsis {if $CONVERT}convert{/if}">
								{$VALUE}
							</div>
						</div>
					{/foreach}
				{/foreach}
			</div>
		{/if}
	</div>
<div class="modal-footer">
	<div class="pull-left">
		<div class="btn-toolbar">
			{if $OPENRECURRINGORDERS || ($EDITVIEW_PERMITTED && !$LOCKEDIT)}
				<div class="btn-group">
					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						{vtranslate('LBL_CHANGE_STATUS',$MODULE_NAME)} <span class="caret"></span>
					</button>
					<ul class="dropdown-menu">
						{foreach  item=ITEM from=Vtiger_Util_Helper::getPickListValues('srecurringorders_status')}
							{if in_array($ITEM, $RESTRICTS_ITEM) || $ITEM eq $RECORD->get('srecurringorders_status')} {continue} {/if}
							<li><a href="#" class="changeStatus" data-state='{$ITEM}' data-id='{$ID}'>{vtranslate($ITEM,$MODULE_NAME)}</a></li>
							{/foreach}
					</ul>
				</div>
			{/if}
			{assign var=ITEM value='PLL_UNREALIZED'}
			{if $CLOSERECURRINGORDERS && $RECORD->get('srecurringorders_status') neq $ITEM}
				<div class="btn-group">
					<button type="button" class="btn btn-danger changeStatus" data-state='{$ITEM}' data-id='{$ID}'>{vtranslate($ITEM, $MODULE_NAME)}</button>
				</div>
			{/if}
		</div>
	</div>
	<button type="button" class="btn btn-warning dismiss" data-dismiss="modal">{vtranslate('LBL_CLOSE', $MODULE_NAME)}</button>
</div>
{/strip}
