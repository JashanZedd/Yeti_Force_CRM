{assign var='MODULEMODEL' value=Vtiger_Module_Model::getInstance($MENU.tabid)}
{assign var='quickCreateModule' value=$MODULEMODEL->isQuickCreateSupported()}
{assign var='singularLabel' value=$MODULEMODEL->getSingularLabelKey()}
{assign var='NAME' value=$MODULEMODEL->getName()}
{if $quickCreateModule == '1' && vtlib_isModuleActive($NAME) && ($PRIVILEGESMODEL->isAdminUser() || $PRIVILEGESMODEL->hasGlobalWritePermission() || $PRIVILEGESMODEL->hasModuleActionPermission($MENU.tabid, 'EditView') ) }
	<li class="quickCreate {$CLASS} {if !$HASPOPUP}hasParentMenu{/if}" data-id="{$MENU.id}" role="menuitem" tabindex="{$TABINDEX}" {if $HASPOPUP}aria-haspopup="{$HASPOPUP}"{/if}>
		<a class="quickCreateModule {if $MENU.hotkey}hotKey{/if}" {if $MENU.hotkey}data-hotkeys="{$MENU.hotkey}"{/if} data-name="{$NAME}" data-url="{$MODULEMODEL->getQuickCreateUrl()}" href="javascript:void(0)">
			{if $MENU.name != ''}{vtranslate($MENU.name,'Menu')}{else}{Vtiger_Menu_Model::vtranslateMenu('LBL_QUICK_CREATE_MODULE',$NAME)}: {Vtiger_Menu_Model::vtranslateMenu($singularLabel,$NAME)}{/if}
		</a>
		{include file='menu/SubMenu.tpl'|@vtemplate_path:$MODULE}
	</li>
{/if}
