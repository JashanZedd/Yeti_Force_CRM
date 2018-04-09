{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<div class="Settings-Vtiger-LibraryMoreInfo modal-header">
		<h3 class="modal-title">{\App\Language::translate('LBL_MORE_LIBRARY_INFO', $QUALIFIED_MODULE)}</h3>
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	</div>
	<div class="modal-body col-md-12">
		{if $RESULT}
			<code>
				<pre>{$FILE_CONTENT}</pre>
			</code>
		{else}
			<div class="alert alert-danger" role="alert">
				{\App\Language::translate('LBL_MISSING_FILE')}
			</div>
		{/if}
	</div>
{/strip}
