{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<div class="tpl-Settings-YetiForce-Status">
		<div class="o-breadcrumb js-breadcrumb widget_header mb-2 d-flex flex-nowrap flex-md-wrap justify-content-between px-2 row">
			<div class="o-breadcrumb__container">
				{include file=\App\Layout::getTemplatePath('BreadCrumbs.tpl', $MODULE)}
			</div>
		</div>
		<div class="o-Settings-YetiForce-Status-table container">
			<div class="row mb-2">
				<div class="col-lg-4"><strong>{\App\Language::Translate('LBL_PARAM_NAME',$QUALIFIED_MODULE)}</strong>
				</div>
				<div class="col-lg-8"><strong>{\App\Language::Translate('LBL_PARAM_VAL',$QUALIFIED_MODULE)}</strong>
				</div>
			</div>
			{foreach $ALL_PARAMS as $CONF_FLAG}
				<div class="row mb-2">
					<div class="col-lg-4">{\App\Language::translate($CONF_FLAG['label'],$QUALIFIED_MODULE)}</div>
					<div class="col-lg-8" align="right">
						{if $CONF_FLAG['type'] === 'bool'}
							<div class="btn-group btn-group-sm btn-group-toggle" data-toggle="buttons">
								<label class="btn btn-secondary{if $CONF_FLAG['value']} active{/if}">
									<input
											name="YF_status_flag[{$CONF_FLAG['name']}]"
											value="1"
											type="radio"
											class="js-YetiForce-Status-var"
											data-js="Status"
											data-flag="{$CONF_FLAG['name']}"
											data-type="{$CONF_FLAG['type']}"
											autocomplete="off"
											{if $CONF_FLAG['value']} checked{/if}
									>
									{\App\Language::Translate('LBL_YES',$QUALIFIED_MODULE)}
								</label>
								<label class="btn btn-secondary{if !$CONF_FLAG['value']} active{/if}">
									<input
											name="YF_status_flag[{$CONF_FLAG['name']}]"
											value="0"
											type="radio"
											class="js-YetiForce-Status-var"
											data-js="Status"
											data-flag="{$CONF_FLAG['name']}"
											data-type="{$CONF_FLAG['type']}"
											autocomplete="off"
											{if !$CONF_FLAG['value']} checked{/if}
									> {\App\Language::Translate('LBL_NO',$QUALIFIED_MODULE)}
								</label>
							</div>
						{else}
							<input
									value="{$CONF_FLAG['value']}"
									type="text"
									class="form-control js-YetiForce-Status-var"
									data-type="{$CONF_FLAG['type']}"
									data-flag="{$CONF_FLAG['name']}"
							/>
						{/if}
					</div>
				</div>
			{/foreach}
		</div>
	</div>
{/strip}