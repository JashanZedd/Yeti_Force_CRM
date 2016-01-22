{*<!-- {[The file is published on the basis of YetiForce Public License that can be found in the following directory: licenses/License.html]} --!>*}

<div class="form-group">
	<label class="col-md-4 control-label">{vtranslate('LBL_NAME', $QUALIFIED_MODULE)}:</label>
	<div class="col-md-7">
		<input name="label" class="form-control" type="text" value="{if $RECORD}{$RECORD->get('label')}{/if}" data-validation-engine="validate[required]" />
	</div>
</div>
<div class="form-group">
	<label class="col-md-4 control-label">{vtranslate('LBL_JAVASCRIPT', $QUALIFIED_MODULE)}:</label>
	<div class="col-md-7">
		<textarea name="dataurl" class="form-control">{if $RECORD}{$RECORD->get('dataurl')}{else}javascript:{/if}</textarea>
	</div>
</div>
{include file='fields/Hotkey.tpl'|@vtemplate_path:$QUALIFIED_MODULE}
<div class="form-group">
	<label class="col-md-4 control-label">{vtranslate('LBL_ICON_NAME', $QUALIFIED_MODULE)}:</label>
	<div class="col-md-7">
		<input name="icon" class="form-control" type="text" value="{if $RECORD}{$RECORD->get('icon')}{/if}"/>
	</div>
</div>
