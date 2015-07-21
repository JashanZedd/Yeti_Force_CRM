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
<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-tab.js"></script>
<style>
    .table tbody tr.error > td {
        background-color: #f2dede;
    }
</style>
<ul id="tabs" class="nav nav-tabs" data-tabs="tabs" style="margin: 20px;">
    <li class="active"><a href="#tab_rc_config" data-toggle="tab">{vtranslate('Roundcube config', 'OSSMail')}</a></li>
</ul>
<div id="my-tab-content" class="tab-content" style="margin: 0 20px;" >
    <div class="editViewContainer tab-pane active" id="tab_rc_config">
        <form id="RCConfigEditorForm" class="form-horizontal">
            <table class="">
                <tbody>
                    {assign var=FIELD_DATA value=$RecordModel->getViewableData()}
                    {foreach key=FIELD_NAME item=FIELD_DETAILS from=$RecordModel->getEditableFields()}
                        <tr><td width="40%"><label class="muted pull-right marginRight10px">{vtranslate($FIELD_DETAILS['label'], 'OSSMail')}</label></td>
                            <td style="border-left: none;" class="row">
                                {if $FIELD_DETAILS['fieldType'] == 'picklist'}
                                    <div class="col-md-8">
                                        <select class="select2 form-control" name="{$FIELD_NAME}">
                                            {foreach item=row from=$FIELD_DETAILS['value']}
                                                <option value="{$row}" {if $row == $FIELD_DATA[$FIELD_NAME]} selected {/if}>
												{if $FIELD_NAME != 'language'}
													{vtranslate($FIELD_NAME|cat:'_'|cat:$row, 'OSSMail')}
												{else}
													{$row}
												{/if}
												</option>
                                            {/foreach}
                                        </select>
                                    </div>
                                {else if $FIELD_DETAILS['fieldType'] == 'checkbox'}
									<div class="col-sm-8">
										<input type="hidden" name="{$FIELD_NAME}" value="false" />
										<input type="checkbox" name="{$FIELD_NAME}" value="true" {if $FIELD_DATA[$FIELD_NAME] == 'true'} checked {/if} />
									</div>
                                {else}
									<div class="col-sm-8">
										<input class="form-control" type="text" name="{$FIELD_NAME}" {if $FIELD_DETAILS['required'] == '1'}required{/if} value="{$FIELD_DATA[$FIELD_NAME]}" />
									</div>
                                    {if $FIELD_NAME == 'upload_maxsize'}&nbsp;{vtranslate('LBL_MB', 'OSSMail')}{/if}
                                {/if}</td></tr>
                            {/foreach}
                </tbody>
            </table>
            <div class="pull-right">
                <button class="btn btn-success saveButton" type="submit" title=""><strong>{vtranslate('LBL_SAVE', 'OSSMail')}</strong></button>
            </div>

        </form>
    </div>
    
</div>
<script type="text/javascript">
$('#status').change(function() {
	$('#confirm').attr('disabled', !this.checked);
});

function saveWidgetConfig(name, value, type) {
	var params = {
		'module': 'OSSMailScanner',
		'action': "SaveWidgetConfig",
		'conf_type': type,
		'name': name,
		'value': value
	}
	AppConnector.request(params).then(
		function(data) {

		},
		function(data, err) {

		}
	);
}
jQuery("#RCConfigEditorForm").submit(function(event) {
	var data = $("#RCConfigEditorForm").serializeFormData();
	var updatedFields = {};
	var status = true;
	jQuery.each(data, function(key, value) {
		if( $( "#RCConfigEditorForm [name='"+key+"']" ).attr('required') != undefined && value == ''){
			var params = {
				title : app.vtranslate('JS_ERROR'),
				text: app.vtranslate('JS_ERROR_EMPTY'),
				type: 'error',
				animation: 'show'
			};
			Vtiger_Helper_Js.showPnotify(params);
			status = false;
		}
		updatedFields[key] = value;
	})
	if(status){
		var params = {
			'module': 'OSSMail',
			'action': "SaveRcConfig",
			'updatedFields': JSON.stringify(updatedFields)
		}
		AppConnector.request(params).then(
				function(data) {
					var response = data['result'];
					if (response['success']) {
						var params = {
							text: response['data'],
							type: 'info',
							animation: 'show'
						};
						Vtiger_Helper_Js.showPnotify(params);
						//console.log(response['data']);
					} else {
						var params = {
							text: response['data'],
							animation: 'show'
						};
						Vtiger_Helper_Js.showPnotify(params);
					}
				},
				function(data, err) {

				}
		);
	}
	return event.preventDefault();
});
</script>
