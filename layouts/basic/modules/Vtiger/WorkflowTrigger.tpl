{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<div class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{\App\Language::translate('LBL_WORKFLOWS_TRIGGER', $MODULE_NAME)}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form">
					{foreach key=KEY item=WORKFLOW from=$WORKFLOWS}
						<div class="form-group" data-workflow_id="{$WORKFLOW->id}">
							<div class="form-check">
								<input type="checkbox" class="form-check-input" id="wf_{$WORKFLOW->id}" value="{$WORKFLOW->id}"/>
								<label class="form-check-label" for="wf_{$WORKFLOW->id}">{\App\Language::translate({$WORKFLOW->description}, 'Settings:Workflows')}</label>
							</div>
						</div>
					{/foreach}
					</div>
				</div>
				<div class="modal-footer flex-wrap">
					<div class="mb-1 mb-sm-0" style="max-width: 255px;">
						{assign var=ROLE_RECORD_MODEL value=Settings_Roles_Record_Model::getInstanceById($USER_MODEL->get('roleid'))}
						<select class="select2 form-control" title="{\App\Language::translate('LBL_USER', $MODULE_NAME)}" name="user" {if $USER_MODEL->isAdminUser() == false && $ROLE_RECORD_MODEL->get('changeowner') == 0}readonly="readonly"{/if}
								{if App\Config::performance('SEARCH_OWNERS_BY_AJAX')}
							data-ajax-search="1" data-ajax-url="index.php?module={$MODULE_NAME}&action=Fields&mode=getOwners&fieldName=assigned_user_id" data-minimum-input="{App\Config::performance('OWNER_MINIMUM_INPUT_LENGTH')}"
								{/if}>
							{if !App\Config::performance('SEARCH_OWNERS_BY_AJAX')}
								{assign var=ALL_ACTIVEUSER_LIST value=\App\Fields\Owner::getInstance()->getAccessibleUsers()}
								{foreach key=OWNER_ID item=OWNER_NAME from=$ALL_ACTIVEUSER_LIST}
									<option value="{$OWNER_ID}" {if $USER_MODEL->getId() eq $OWNER_ID} selected {/if}>{$OWNER_NAME}</option>
								{/foreach}
							{else}
								<option value="{$USER_MODEL->getId()}">{$USER_MODEL->getName()}</option>
							{/if}
						</select>
					</div>
					<button class="btn btn-success" type="submit">
						<span class="fas fa-check mr-1"></span>
						<strong>{\App\Language::translate('LBL_EXECUTE', $MODULE_NAME)}</strong>
					</button>
					<button class="btn btn-danger" type="reset" data-dismiss="modal">
						<span class="fas fa-times mr-1"></span>
						<strong>{\App\Language::translate('LBL_CANCEL', $MODULE_NAME)}</strong>
					</button>
				</div>
			</div>
		</div>
	</div>
{/strip}
