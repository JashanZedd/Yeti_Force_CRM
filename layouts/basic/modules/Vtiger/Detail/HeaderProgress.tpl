{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<!-- tpl-Base-Detail-HeaderProgress -->
	{if isset($FIELDS_HEADER['progress'])}
		{assign var=CLOSE_STATES value=\App\Fields\Picklist::getCloseStates($MODULE_MODEL->getId(), false)}
		{foreach from=$FIELDS_HEADER['progress'] key=NAME item=FIELD_MODEL}
			{if !$RECORD->isEmpty($NAME)}
				{assign var=PICKLIST_OF_FIELD value=$FIELD_MODEL->getPicklistValues()}
				{assign var=PICKLIST_VALUES value=\App\Fields\Picklist::getValues($NAME)}
				<div class="c-arrows px-3 w-100">
					<ul class="c-arrows__container js-header-progress-bar" data-picklist-name="{$NAME}"
						data-js="container">
						{assign var=ARROW_CLASS value="before"}
						{foreach from=$PICKLIST_VALUES item=VALUE_DATA name=picklistValues}
							{assign var=PICKLIST_LABEL value=$FIELD_MODEL->getDisplayValue($VALUE_DATA['picklistValue'], false, false, true)}
							<li class="c-arrows__item {if $smarty.foreach.picklistValues.first}first{/if} {if $VALUE_DATA['picklistValue'] eq $RECORD->get($NAME)}active{assign var=ARROW_CLASS value="after"}{else}{$ARROW_CLASS}{/if}{if $RECORD->isEditable() && $FIELD_MODEL->isAjaxEditable() && $VALUE_DATA['picklistValue'] !== $RECORD->get($NAME) && isset($PICKLIST_OF_FIELD[$VALUE_DATA['picklistValue']])} u-cursor-pointer js-access{/if}"
								data-picklist-value="{$VALUE_DATA['picklistValue']}"
								data-picklist-label="{\App\Purifier::encodeHtml($PICKLIST_LABEL)}"
								data-js="confirm|click|data">
								<a class="c-arrows__link pr-1">
									{if isset($CLOSE_STATES[$VALUE_DATA['picklist_valueid']]) }
									<span class="c-arrows__icon fas fa-lock"></span>
									{/if}
									<span class="c-arrows__text{if !empty($VALUE_DATA['description'])} js-popover-tooltip"
										  data-js="popover"
										  data-trigger="hover focus"
										  data-content="{\App\Purifier::encodeHtml($VALUE_DATA['description'])}"
									{else}"{/if}>
									{$PICKLIST_LABEL}
									</span>
								</a>
							</li>
						{/foreach}
					</ul>
				</div>
			{/if}
		{/foreach}
	{/if}
	<!-- /tpl-Base-Detail-HeaderProgress -->
{/strip}
