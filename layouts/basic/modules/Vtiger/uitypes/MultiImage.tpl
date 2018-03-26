{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	{assign var="FIELD_INFO" value=\App\Purifier::encodeHtml(\App\Json::encode($FIELD_MODEL->getFieldInfo()))}
	{assign var="SPECIAL_VALIDATOR" value=$FIELD_MODEL->getValidator()}
	{assign var=FIELD_VALUE value=$FIELD_MODEL->getEditViewDisplayValue($FIELD_MODEL->get('fieldvalue'),$RECORD)}
	<input type="hidden" name="{$FIELD_MODEL->getFieldName()}"
		   id="{$MODULE_NAME}_editView_fieldName_{$FIELD_MODEL->getFieldName()}" value="{$FIELD_VALUE}"
		   data-validation-engine="validate[{if ($FIELD_MODEL->isMandatory() eq true)} required,{/if}funcCall[Vtiger_Base_Validator_Js.invokeValidation]]"
		   data-fieldinfo='{$FIELD_INFO}'
		   {if !empty($SPECIAL_VALIDATOR)}data-validator={\App\Json::encode($SPECIAL_VALIDATOR)}{/if}>
	<div class="c-multi-image border rounded p-2">
		<div class="fileinput-button btn btn-primary">
			<input class="c-multi-image__file" type="file" name="files[]"
				   data-url="index.php?module={$FIELD_MODEL->getModuleName()}&view=FileUpload&inputName={$FIELD_MODEL->getFieldName()}&fileType=image"
				   multiple>
			<span class="fa fa-plus"></span> {\App\Language::translate('BTN_ADD_FILE', $MODULE_NAME)}
		</div>
		<div class="c-multi-image__result" data-name="{$FIELD_MODEL->getFieldName()}">
			{if $RECORD}
				{assign var="RECORD_ID" value=$RECORD->getId()}
				{assign var="IMAGES" value=$FIELD_VALUE}
			{else}
				{assign var="RECORD_ID" value=''}
				{assign var="IMAGES" value=[]}
			{/if}
			{foreach key=ITER item=IMAGE_INFO from=$IMAGES}
				<div class="c-multi-image__image float-left" title="{$IMAGE_INFO.name}">
					<div class="c-multi-image__image-content">
						<img src="{$FIELD_MODEL->getUITypeModel()->getImagePath($IMAGE_INFO.attachmentid, $RECORD_ID)}"
							 class="c-multi-image__image-content-img">
					</div>
				</div>
			{/foreach}
		</div>
		<div class="c-multi-image__progress progress d-none my-2">
			<div class="c-multi-image__progress-bar progress-bar progress-bar-striped progress-bar-animated"
				 role="progressbar"
				 style="width: 0%"></div>
		</div>
	</div>
{/strip}
