{*<!-- {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} --!>*}
{strip}
	{if isset($ITEM_DATA['name'])}
		{assign var="REFERENCE_MODULE" value=Vtiger_Functions::getCRMRecordType($ITEM_DATA['name'])}
	{elseif $MAIN_PARAMS}
		{assign var="REFERENCE_MODULE" value=reset($MAIN_PARAMS['modules'])}
	{/if}
	<tr class="inventoryRow" numrow="{$ROW_NO}">
		<td>
			<span class="glyphicon glyphicon-trash deleteRow cursorPointer {if $KEY == 0 }hide{/if}" title="{vtranslate('LBL_DELETE',$MODULE)}"></span>
			&nbsp;&nbsp;<a class="dragHandle"><img src="{vimage_path('drag.png')}" border="0" alt="{vtranslate('LBL_DRAG',$MODULE)}"/></a>
			<input name="seq{$ROW_NO}" type="hidden" value="{$ROW_NO}" class="sequence" />
			{if $COUNT_FIELDS2 > 0}
				<br/><br/>
				<span class="btn btn-default btn-xs toggleVisibility" data-status="0" href="#">
					<span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>	
				</span>
			{/if}
		</td>
		{foreach item=FIELD from=$FIELDS[1]}
			<td class="col{$FIELD->getName()}{if !$FIELD->isVisible($INVENTORY_ROWS)} hide{/if} textAlignRight" colspan="{$FIELD->get('colspan')}">
				{assign var="FIELD_TPL_NAME" value="inventoryfields/"|cat:$FIELD->getTemplateName('EditView',$MODULE)}
				{include file=$FIELD_TPL_NAME|@vtemplate_path:$MODULE ITEM_VALUE=$ITEM_DATA[$FIELD->get('columnname')]}
			</td>
		{/foreach}
	</tr>
	{if $FIELDS[2] neq 0}
		<tr class="inventoryRowExpanded numRow{$ROW_NO} hide" numrowex="{$ROW_NO}">
			{foreach item=FIELD from=$FIELDS[2]}
				{if $FIELD->get('colspan') eq 0}
					{assign var="COLSPAN" value=$COUNT_FIELDS1+1}
				{else}
					{assign var="COLSPAN" value=$FIELD->get('colspan')}
				{/if}
				<td class="col{$FIELD->getName()}{if !$FIELD->isVisible($INVENTORY_ROWS)} hide{/if}" colspan="{$COLSPAN}">
					{assign var="FIELD_TPL_NAME" value="inventoryfields/"|cat:$FIELD->getTemplateName('EditView',$MODULE)}
					{include file=$FIELD_TPL_NAME|@vtemplate_path:$MODULE ITEM_VALUE=$ITEM_DATA[$FIELD->get('columnname')]}
				</td>
			{/foreach}
		</tr>
	{/if}
{/strip}
