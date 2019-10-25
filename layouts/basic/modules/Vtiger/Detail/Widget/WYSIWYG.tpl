{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
<!-- tpl-Base-Detail-Widget-WYSIWYG -->
	{assign var=WIDGET_UID value="id-{\App\Layout::getUniqueId(\App\Language::translate($WIDGET['label'],$MODULE_NAME))}"}
	<div class="tpl-Base-Detail-WYSIWYG c-detail-widget js-detail-widget c-detail-widget--wysiwyg"
		 data-js="container">
		<div class="c-detail-widget__header js-detail-widget-header collapsed" data-js="container|value">
			<div class="d-flex align-items-center py-1">
				<div class="c-detail-widget__toggle collapsed" id="{$WIDGET_UID}" data-toggle="collapse" data-target="#{$WIDGET_UID}-collapse" aria-expanded="false" aria-controls="{$WIDGET_UID}-collapse">
						<span class="mdi mdi-chevron-up" alt="{\App\Language::translate('LBL_EXPAND_BLOCK')}"></span>
				<span class="mdi mdi-chevron-down" alt="{\App\Language::translate('LBL_COLLAPSE_BLOCK')}"></span>
					</div>
				<h5 class="mb-0 py-1">{\App\Language::translate($WIDGET['label'],$MODULE_NAME)}</h5>
			</div>
		</div>
		<div class="c-detail-widget__content js-detail-widget-content collapse multi-collapse pt-1" id="{$WIDGET_UID}-collapse" data-storage-key="{$WIDGET['id']}"  aria-labelledby="{$WIDGET_UID}" data-js="container|value">
			{assign var=FULL_TEXT value=$RECORD->getDisplayValue($WIDGET['data']['field_name'])}
			{assign var=TRUNCATE_TEXT value=\App\TextParser::htmlTruncate($FULL_TEXT,600,true,$IS_TRUNCATED)}
			{if !empty($TRUNCATE_TEXT)}
				<div class="mt-0 mx-0 moreContent">
					<div class="table-responsive">
						<span class="teaserContent">
							{$TRUNCATE_TEXT}
						</span>
						{if $IS_TRUNCATED}
							<span class="fullContent d-none">
								{$FULL_TEXT}
							</span>
						{/if}
					</div>
					{if $IS_TRUNCATED}
						<div class="my-1 text-right">
							<button type="button" class="btn btn-info btn-sm moreBtn"
									data-on="{\App\Language::translate('LBL_MORE_BTN')}"
									data-off="{\App\Language::translate('LBL_HIDE_BTN')}">{\App\Language::translate('LBL_MORE_BTN')}</button>
						</div>
					{/if}
				</div>
			{else}
				<div class="summaryWidgetContainer">
					<p class="textAlignCenter">
						{\App\Language::translate('LBL_NO_ENTRIES')}
					</p>
				</div>
			{/if}
		</div>
	</div>
<!-- /tpl-Base-Detail-Widget-WYSIWYG -->
{/strip}
