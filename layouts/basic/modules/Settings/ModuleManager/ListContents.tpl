{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
* Contributor(s): YetiForce.com
********************************************************************************/
-->*}
{strip}
	<div class="" id="moduleManagerContents">
		<div class="widget_header row">
			<div class="col-md-7">
				{include file=\App\Layout::getTemplatePath('BreadCrumbs.tpl', $MODULE)}
				{if isset($SELECTED_PAGE)}
					{\App\Language::translate($SELECTED_PAGE->get('description'),$QUALIFIED_MODULE)}
				{/if}
			</div>
			<div class="col-md-5">
				<span class="btn-toolbar float-right mt-1">
					<span class="btn-group mr-1">
						<button class="btn btn-success createModule" type="button">
							<span class="fas fa-desktop"></span>&nbsp;&nbsp;
							<strong>{\App\Language::translate('LBL_CREATE_MODULE', $QUALIFIED_MODULE)}</strong>
						</button>
					</span>
					{if \AppConfig::main('systemMode') !== 'demo'}
						<span class="btn-group">
							<button class="btn btn-primary" type="button" onclick='window.location.href = "{$IMPORT_USER_MODULE_URL}"'>
								<span class="fas fa-download"></span>&nbsp;&nbsp;
								<strong>{\App\Language::translate('LBL_IMPORT_ZIP', $QUALIFIED_MODULE)}</strong>
							</button>
						</span>
					{/if}
				</span>
			</div>
		</div>
		<div class="contents">
			<table class="table table-bordered table-sm">
				<thead>
					<tr class="blockHeader">
						<th>
							<span>{\App\Language::translate('LBL_LIBRARY_NAME', $QUALIFIED_MODULE)}</span>
						</th>
						<th>
							<span>{\App\Language::translate('LBL_LIBRARY_DIR', $QUALIFIED_MODULE)}</span>
						</th>
						<th>
							<span>{\App\Language::translate('LBL_LIBRARY_URL', $QUALIFIED_MODULE)}</span>
						</th>
						<th>
							<span>{\App\Language::translate('LBL_LIBRARY_STATUS', $QUALIFIED_MODULE)}</span>
						</th>
						<th>
							<span>{\App\Language::translate('LBL_LIBRARY_ACTION', $QUALIFIED_MODULE)}</span>
						</th>
					</tr>
				</thead>
				<tbody>
					{foreach key=NAME item=LIBRARY from=Settings_ModuleManager_Library_Model::getAll()}
						<tr>
							<td><strong>{$NAME}</strong></td>
							<td>{$LIBRARY['dir']}</td>
							<td><a href="{$LIBRARY['url']}">{$LIBRARY['url']}</a></td>
							<td>
								{if $LIBRARY['status'] == 1}
									<span class="badge badge-success bigLabel">
										{\App\Language::translate('LBL_LIBRARY_DOWNLOADED', $QUALIFIED_MODULE)}&nbsp;&nbsp;
										<span class="far fa-check-circle"></span>
									</span>
								{elseif $LIBRARY['status'] == 2}
									<span class="badge badge-warning bigLabel">
										{\App\Language::translate('LBL_LIBRARY_NEEDS_UPDATING', $QUALIFIED_MODULE)}&nbsp;&nbsp;
										<span class="fas fa-info-circle"></span>
									</span>
								{else}
									<span class="badge badge-danger bigLabel">
										{\App\Language::translate('LBL_LIBRARY_NO_DOWNLOAD', $QUALIFIED_MODULE)}&nbsp;&nbsp;
										<span class="fas fa-ban"></span>
									</span>
								{/if}
							</td>
							<td class="text-center">
								<span class="btn-group">
									{if $LIBRARY['status'] === 0}
										<form method="POST" action="index.php?module=ModuleManager&parent=Settings&action=Library&mode=download&name={$NAME}">
											<button type="submit" class="btn btn-primary btn-sm">
												<span class="fas fa-download mr-1"></span>
												<strong>{\App\Language::translate('BTN_LIBRARY_DOWNLOAD', $QUALIFIED_MODULE)}</strong>
											</button>
										</form>
									{else}
										<form method="POST" action="index.php?module=ModuleManager&parent=Settings&action=Library&mode=update&name={$NAME}">
											<button type="submit" class="btn btn-primary btn-sm">
												<span class="fas fa-redo-alt mr-1"></span>
												<strong>{\App\Language::translate('BTN_LIBRARY_UPDATE', $QUALIFIED_MODULE)}</strong>
											</button>
										</form>
									{/if}
								</span>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
			<br />
			{assign var=COUNTER value=0}
			<table class="table table-bordered table-sm">
				<tr>
					{foreach item=MODULE_MODEL key=MODULE_ID from=$ALL_MODULES}
						{assign var=MODULE_NAME value=$MODULE_MODEL->get('name')}
						{assign var=MODULE_ACTIVE value=$MODULE_MODEL->isActive()}
						{if $COUNTER eq 2}
						</tr><tr>
							{assign var=COUNTER value=0}
						{/if}
						<td>
							<div class="row px-3">
								<div class="col-1 p-2">
									<input type="checkbox" value="" name="moduleStatus" data-module="{$MODULE_NAME}" data-module-translation="{\App\Language::translate($MODULE_NAME, $MODULE_NAME)}" {if $MODULE_MODEL->isActive()}checked{/if} />
								</div>
								<div class="col-1 p-2 {if !$MODULE_ACTIVE}dull {/if}">
									<span class="fa-2x userIcon-{$MODULE_NAME}"></span>
								</div>
								<div class="col-5 p-2 {if !$MODULE_ACTIVE}dull {/if}">
									<h5 class="no-margin">{\App\Language::translate($MODULE_NAME, $MODULE_NAME)}</h5>
								</div>
								<div class="col-5 p-2">
									{if $MODULE_MODEL->get('customized')}
										<button type="button" class="deleteModule btn btn-danger btn-sm float-right ml-2" name="{$MODULE_NAME}">{\App\Language::translate('LBL_DELETE')}</button>
									{/if}
									{if $MODULE_MODEL->isExportable()}
										<form method="POST" action="index.php?module=ModuleManager&parent=Settings&action=ModuleExport&mode=exportModule&forModule={$MODULE_NAME}">
											<button type="submit" class="btn btn-primary btn-sm float-right ml-2"><i class="far fa-arrow-alt-circle-down"></i></button>
										</form>
									{/if}
									{assign var=SETTINGS_LINKS value=$MODULE_MODEL->getSettingLinks()}
									{if !in_array($MODULE_NAME, $RESTRICTED_MODULES_LIST) && (count($SETTINGS_LINKS) > 0)}
										<div class="btn-group {if !$MODULE_ACTIVE}d-none{/if}" role="group">
											<button class="btn dropdown-toggle btn-light" data-toggle="dropdown">
												<strong>{\App\Language::translate('LBL_SETTINGS', $QUALIFIED_MODULE)}</strong>&nbsp;<i class="caret"></i>
											</button>
											<div class="dropdown-menu float-right">
												{foreach item=SETTINGS_LINK from=$SETTINGS_LINKS}
													<a class="dropdown-item" href="{$SETTINGS_LINK['linkurl']}">{\App\Language::translate($SETTINGS_LINK['linklabel'], $MODULE_NAME)}</a>
												{/foreach}
											</div>
										</div>
									{/if}
								</div>
								{assign var=COUNTER value=$COUNTER+1}
						</td>
					{/foreach}
				</tr>
			</table>
		</div>
	</div>
{/strip}
