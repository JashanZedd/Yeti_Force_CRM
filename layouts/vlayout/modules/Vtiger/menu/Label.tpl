<li class="menuLabel {$CLASS} {if !$HASPOPUP}hasParentMenu{/if}" data-id="{$MENU.id}" role="menuitem"  {if $HASPOPUP}aria-haspopup="{$HASPOPUP}"{/if}>
	<a {if $CLASS} data-toggle="dropdown" class="dropdown-toggle" {/if}href="#">{Vtiger_Menu_Model::vtranslateMenu($MENU.name,'Menu')}</a>
	{include file='menu/SubMenu.tpl'|@vtemplate_path:$MODULE}
</li>
