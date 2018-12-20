{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<!-- tpl-Base-inventoryfields-EditViewName -->
	{if $REFERENCE_MODULE}
		<div class="rowName">
			{assign var="FIELD_NAME" value="inventory[{$ROW_NO}][{$FIELD->getColumnName()}]"}
			{assign var="FIELD_INFO" value=\App\Purifier::encodeHtml($FIELD->get('params'))}
			<div class="input-group input-group-sm">
				<input name="popupReferenceModule" type="hidden" data-multi-reference="1" value="{$REFERENCE_MODULE}"/>
				<div class="input-group-prepend">
					{if !$FIELD->isReadOnly()}
						<span class="input-group-text clearReferenceSelection u-cursor-pointer js-popover-tooltip"
							  data-js="popover" data-content="{\App\Language::translate('LBL_CLEAR',$MODULE_NAME)}">
						<span id="{$MODULE_NAME}_editView_fieldName_{$FIELD_NAME}_clear" class="fas fa-times-circle"></span>
					</span>
					{/if}
				</div>
				<input id="{$FIELD_NAME}_display" name="{$FIELD_NAME}_display" type="text"
					   title="{$FIELD->getEditValue($ITEM_VALUE)}"
					   class="marginLeftZero form-control autoComplete recordLabel js-{$FIELD->getColumnName()}_display"
					   {if !empty($ITEM_VALUE)}readonly="true"{/if}
					   value="{$FIELD->getEditValue($ITEM_VALUE)}"
					   data-validation-engine="validate[{if $FIELD->isMandatory()} required,{/if}funcCall[Vtiger_Base_Validator_Js.invokeValidation]]"
					   data-fieldinfo="{$FIELD_INFO}"
						{if $FIELD->isReadOnly()} readonly="readonly"{else} placeholder="{\App\Language::translate('LBL_TYPE_SEARCH',$MODULE_NAME)}"{/if}/>
				<div class="input-group-append">
					{if $FIELD->get('displaytype') != 10}
						<span class="input-group-text relatedPopup u-cursor-pointer js-popover-tooltip"
							  data-js="popover" data-content="{\App\Language::translate('LBL_SELECT',$MODULE_NAME)}">
						<span id="{$MODULE_NAME}_editView_fieldName_{$FIELD_NAME}_select" class="fas fa-search"></span>
					</span>
					{/if}
					{assign var=REFERENCE_MODULE_MODEL value=Vtiger_Module_Model::getInstance($REFERENCE_MODULE)}
					{if $REFERENCE_MODULE_MODEL->isQuickCreateSupported() && $FIELD->get('displaytype') != 10}
						<span class="input-group-text u-cursor-pointer createReferenceRecord js-popover-tooltip"
							  data-js="popover" data-content="{\App\Language::translate('LBL_CREATE',$MODULE)}">
						<span id="{$REFERENCE_MODULE}_editView_fieldName_{$FIELD_NAME}_create" class="fas fa-plus"></span>
					</span>
					{/if}
				</div>
				<input name="{$FIELD_NAME}" type="hidden" value="{$ITEM_VALUE}" title="{$ITEM_VALUE}"
					   class="sourceField js-{$FIELD->getColumnName()}" data-type="inventory" data-displayvalue='{$FIELD->getEditValue($ITEM_VALUE)}'
					   data-fieldinfo='{$FIELD_INFO}' data-columnname="{$FIELD->getColumnName()}"
					   {if $FIELD->isReadOnly()}readonly="readonly"{/if} />
			</div>
			<div class="subProductsContainer">
				<ul class="float-left">
				</ul>
			</div>
		</div>
	{/if}
	<!-- /tpl-Base-inventoryfields-EditViewName -->
{/strip}
