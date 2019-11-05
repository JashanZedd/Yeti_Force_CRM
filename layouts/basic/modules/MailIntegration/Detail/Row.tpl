{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
<!-- tpl-MailIntegration-Detail-Row -->
	<li class="list-group-item list-group-item-action py-0 px-2 js-row-click" data-id="{$ROW['id']}" data-module="{$ROW['module']}">
		{assign var=DETAIL_VIEW_PERMITTED value=\App\Privilege::isPermitted($ROW['module'], 'DetailView', $ROW['id'])}
	  <div class="d-flex w-100 align-items-center">
			<a class="modCT_{$ROW['module']} js-record-link js-popover-tooltip--record small u-text-unset u-text-ellipsis" {if $DETAIL_VIEW_PERMITTED}href="{$URL}index.php?module={$ROW['module']}&view=Detail&record={$ROW['id']}"{/if} target="_blank">
				<span class="relatedModuleIcon yfm-{$ROW['module']} mr-1" aria-hidden="true"></span>
				<span class="relatedName">{$ROW['label']}</span>
			</a>
			{if $DETAIL_VIEW_PERMITTED}
				<div class="ml-auto btn-group btn-group-sm" role="group" aria-label="record actions">
					{if \App\Privilege::isPermitted('Calendar','CreateView')}
						<button class="js-add-related-record btn u-text-unset js-popover-tooltip" data-module="Calendar" data-js="popover" data-content="{\App\Language::translate('LBL_ADD_CALENDAR',$MODULE_NAME)}">
							<span class="yfm-Calendar" aria-hidden="true"></span>
						</button>
					{/if}
					{if \App\Privilege::isPermitted('ModComments','CreateView')}
						<button class="js-add-related-record btn u-text-unset js-popover-tooltip" data-module="ModComments" data-js="popover" data-content="{\App\Language::translate('LBL_ADD_MODCOMMENTS',$MODULE_NAME)}">
							<span class="yfm-ModComments"></span>
						</button>
					{/if}
					{if $REMOVE_RECORD && \App\Privilege::isPermitted($ROW['module'], 'RemoveRelation')}
						<button class="js-remove-record btn u-text-unset js-popover-tooltip" data-js="popover" data-content="{\App\Language::translate('LBL_REMOVE_RELATION',$MODULE_NAME)}">
							<span class="fas fa-times"></span>
						</button>
					{/if}
				</div>
			{/if}
		</div>
	</li>
<!-- /tpl-MailIntegration-Detail-Row -->
{/strip}
