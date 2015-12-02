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
<div class='col-md-12 paddingLRZero'>
	<div>
		<div><strong>{'LBL_IMPORT_STEP_2'|@vtranslate:$MODULE}:</strong></div>
		<div class="big">{'LBL_IMPORT_STEP_2_DESCRIPTION'|@vtranslate:$MODULE}</div>
		<div>&nbsp;</div>
	</div>
	<div id="file_type_container">
		<div class="col-md-4"><span>{'LBL_FILE_TYPE'|@vtranslate:$MODULE}</span></div>
		<div class="col-md-6 paddingBottom10">
			<select name="type" class="form-control" id="type" title="{vtranslate('LBL_FILE_TYPE', $MODULE)}" onchange="ImportJs.handleFileTypeChange();">
				{foreach item=_FILE_TYPE from=$SUPPORTED_FILE_TYPES}
				<option value="{$_FILE_TYPE}">{$_FILE_TYPE|@vtranslate:$MODULE}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div  id="file_encoding_container">
		<div class="col-md-4"><span>{'LBL_CHARACTER_ENCODING'|@vtranslate:$MODULE}</span></div>
		<div class="col-md-6 paddingBottom10">
			<select name="file_encoding" class="form-control" id="file_encoding" title="{vtranslate('{LBL_CHARACTER_ENCODING', $MODULE)}">
				{foreach key=_FILE_ENCODING item=_FILE_ENCODING_LABEL from=$SUPPORTED_FILE_ENCODING}
				<option value="{$_FILE_ENCODING}">{$_FILE_ENCODING_LABEL|@vtranslate:$MODULE}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div  id="delimiter_container">
		<div class="col-md-4"><span>{'LBL_DELIMITER'|@vtranslate:$MODULE}</span></div>
		<div class="col-md-6 paddingBottom10">
			<select name="delimiter" class="form-control" id="delimiter" title="{vtranslate('LBL_DELIMITER', $MODULE)}">
				{foreach key=_DELIMITER item=_DELIMITER_LABEL from=$SUPPORTED_DELIMITERS}
				<option value="{$_DELIMITER}">{$_DELIMITER_LABEL|@vtranslate:$MODULE}</option>
				{/foreach}
			</select>
		</div>
	</div>
	<div id="has_header_container">
		<div class="col-md-4"><span>{'LBL_HAS_HEADER'|@vtranslate:$MODULE}</span></div>
		<div class="col-md-6"><input type="checkbox" id="has_header" name="has_header" title="{vtranslate('LBL_HAS_HEADER', $MODULE)}" checked /></div>
	</div>
</div>
{/strip}
