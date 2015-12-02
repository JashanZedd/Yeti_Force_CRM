{*<!--
/*+***********************************************************************************************************************************
 * The contents of this file are subject to the YetiForce Public License Version 1.1 (the "License"); you may not use this file except
 * in compliance with the License.
 * Software distributed under the License is distributed on an "AS IS" basis, WITHOUT WARRANTY OF ANY KIND, either express or implied.
 * See the License for the specific language governing rights and limitations under the License.
 * The Original Code is YetiForce.
 * The Initial Developer of the Original Code is YetiForce. Portions created by YetiForce are Copyright (C) www.yetiforce.com. 
 * All Rights Reserved.
 *************************************************************************************************************************************/
-->*}
<div class="modal fade" tabindex="-1">
	<div class="modal-dialog">
        <div class="modal-content">
			<form class="form-modalAddWidget">
				<input type="hidden" name="wid" value="{$WID}">
				<input type="hidden" name="type" value="{$TYPE}">
				<div class="modal-header">
					<button type="button" data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE', $QUALIFIED_MODULE)}">×</button>
					<h3 id="massEditHeader" class="modal-title">{vtranslate('Add widget', $QUALIFIED_MODULE)}</h3>
				</div>
				<div class="modal-body">
					<div class="modal-Fields">
						<div class="row">
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Type widget', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								{vtranslate($TYPE, $QUALIFIED_MODULE)}
							</div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Label', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls"><input name="label" class="form-control" type="text" value="{$WIDGETINFO['label']}" /></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('No left margin', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<input name="nomargin" class="" type="checkbox" value="1" {if $WIDGETINFO['nomargin'] == 1}checked{/if}/>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('No left margin info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('No left margin', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Limit entries', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<input name="limit" class="form-control" type="text" value="{$WIDGETINFO['data']['limit']}"/>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('Limit entries info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('Limit entries', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Related module', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<select name="relatedmodule" class="select2 form-control marginLeftZero">
									{foreach from=$RELATEDMODULES item=item key=key}
										<option value="{$item['related_tabid']}" {if $WIDGETINFO['data']['relatedmodule'] == $item['related_tabid']}selected{/if} >{vtranslate($item['label'], $item['name'])}</option>
									{/foreach}
								</select>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('Related module info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('Related module', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Columns', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<select name="columns" class="select2 form-control marginLeftZero">
									{foreach from=$MODULE_MODEL->getColumns() item=item key=key}
										<option value="{$item}" {if $WIDGETINFO['data']['columns'] == $item}selected{/if} >{$item}</option>
									{/foreach}
								</select>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('Columns info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('Columns', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Add button', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<input name="action" class="" type="checkbox" value="1" {if $WIDGETINFO['data']['action'] == 1}checked{/if}/>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('Add button info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('Add button', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Select button', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<input name="actionSelect" class="" type="checkbox" value="1" {if $WIDGETINFO['data']['actionSelect'] == 1}checked{/if}/>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('Select button info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('Select button', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('No message', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<input name="no_result_text" class="" type="checkbox" value="1" {if $WIDGETINFO['data']['no_result_text'] == 1}checked{/if}/>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('No message info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('No message', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('Filter', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<input type="hidden" name="filter_selected" value="{$WIDGETINFO['data']['filter']}">
								<select name="filter" class="select2 form-control marginLeftZero">
									<option value="-">{vtranslate('None', $QUALIFIED_MODULE)}</option>
								</select>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('Filter info', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('Filter', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
							<div class="form-group form-group-sm">
							<label class="col-md-4 text-right control-label">{vtranslate('LBL_CONDITION', $QUALIFIED_MODULE)}:</label>
							<div class="col-md-7 controls">
								<input type="hidden" name="checkbox_selected" value="{$WIDGETINFO['data']['checkbox']}">
								<select name="checkbox" class="select2 form-control marginLeftZero">
									<option value="-">{vtranslate('None', $QUALIFIED_MODULE)}</option>
								</select>

							</div><div class="col-md-1"><a href="#" class="HelpInfoPopover pull-right" title="" data-placement="top" data-content="{vtranslate('LBL_CONDITION_INFO', $QUALIFIED_MODULE)}" data-original-title="{vtranslate('LBL_CONDITION', $QUALIFIED_MODULE)}"><i class="glyphicon glyphicon-info-sign"></i></a></div>
							</div>
						</div>
					</div>
				</div>
				{include file='ModalFooter.tpl'|@vtemplate_path:$QUALIFIED_MODULE}
			</form>
		</div>
	</div>
</div>
