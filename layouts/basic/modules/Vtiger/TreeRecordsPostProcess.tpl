{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<!-- tpl-TreeRecordsPostProcess -->
	</div>
	</div>
	</div>
	<div class="siteBarRight">
		<div class="btn btn-block toggleSiteBarRightButton"
			 title="{\App\Language::translate('LBL_RIGHT_PANEL_SHOW_HIDE', $MODULE)}">
			<span class="fas fa-chevron-right"></span>
		</div>
		<div class="siteBarContent">
			<div class="row">
				<div class="col-12 paddingTop10">
					<h5>{\App\Language::translate('LBL_FILTERING',$MODULE)}</h5>
				</div>
				<div class="col-12 paddingBottom10">
					<select class="select2 form-control" id="moduleFilter">
						{foreach key=GROUP_LABEL item=GROUP_CUSTOM_VIEWS from=$CUSTOM_VIEWS}
							<optgroup label="{\App\Language::translate('LBL_CV_GROUP_'|cat:strtoupper($GROUP_LABEL))}">
								{foreach item="CUSTOM_VIEW" from=$GROUP_CUSTOM_VIEWS}
									<option value="{$CUSTOM_VIEW->get('cvid')}" {if $VIEWID neq '' && $VIEWID neq '0'  && $VIEWID == $CUSTOM_VIEW->getId()} selected="selected" {elseif ($VIEWID == '' or $VIEWID == '0')&& $CUSTOM_VIEW->isDefault() eq 'true'} selected="selected" {/if}>
										{\App\Language::translate($CUSTOM_VIEW->get('viewname'), $MODULE)}
										{if $GROUP_LABEL neq 'Mine' && $GROUP_LABEL neq 'System'} [ {$CUSTOM_VIEW->getOwnerName()} ]  {/if}
									</option>
								{/foreach}
							</optgroup>
						{/foreach}
					</select>
				</div>
			</div>
			<input type="hidden" id="treeListValues" value="{\App\Purifier::encodeHtml($TREE_LIST)}">
			<div id="treeListContents"></div>
		</div>
	</div>
	</div>
	<!-- /tpl-TreeRecordsPostProcess -->
{/strip}
