{*<!-- {[The file is published on the basis of YetiForce Public License 5.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<!-- tpl-Settings-Wapro-ListSynchronizerModal -->
	<div class="modal-body pb-0">
		<textarea rows="15" disabled>{$WAPRO_MODEL->getInfo()}</textarea>
		<form>
			{assign var=SYNCHRONIZERS value=$WAPRO_MODEL->config['synchronizer']}
			{foreach from=$WAPRO_MODEL->getAllSynchronizers() key=NAME item=SYNCHRONIZER}
				<div class="form-group form-check">
					<input type="checkbox" value="{$NAME}" class="form-check-input js-synchronizer" id="wapro{$NAME}" {if in_array($NAME,$SYNCHRONIZERS)} checked{/if} data-js="container">
					<label class="form-check-label ml-2" for="wapro{$NAME}">{\App\Language::translate($SYNCHRONIZER::NAME, $QUALIFIED_MODULE)}</label>
				</div>
			{/foreach}
		</form>
	</div>
	<div class="modal-footer">
		<div class="float-right">
			<button class="btn btn-success js-modal__save mr-2" type="button" name="saveButton">
				<span class="fas fa-check mr-2"></span>
				<strong>{\App\Language::translate('LBL_SAVE', $MODULE)}</strong>
			</button>
			<button class="btn btn-danger" type="reset" data-dismiss="modal">
				<span class="fas fa-times mr-2"></span>
				<strong>{\App\Language::translate('LBL_CANCEL', $MODULE)}</strong>
			</button>
		</div>
	</div>
	<!-- /tpl-Settings-Wapro-ListSynchronizerModal -->
{/strip}
