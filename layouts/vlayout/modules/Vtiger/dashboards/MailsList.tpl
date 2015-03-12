<div class="dashboardWidgetHeader">
	{foreach key=index item=cssModel from=$STYLES}
		<link rel="{$cssModel->getRel()}" href="{$cssModel->getHref()}" type="{$cssModel->getType()}" media="{$cssModel->getMedia()}" />
	{/foreach}
	{foreach key=index item=jsModel from=$SCRIPTS}
		<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
	{/foreach}

	<table width="100%" cellspacing="0" cellpadding="0">
		<tbody>
			<tr>
				<td class="span4">
					<div class="dashboardTitle textOverflowEllipsis" title="{vtranslate($WIDGET->getTitle(), 'OSSMail')}"><b>&nbsp;&nbsp;{vtranslate($WIDGET->getTitle(), 'OSSMail')}</b></div>
				</td>
				<td class="span5">
					<div style="float:right;">
						<select class="mailUserList" id="mailUserList" name="type" style='width:200px;margin-bottom:0px'>
							{if count($ACCOUNTSLIST) eq 0}
								<option value="-">{vtranslate('--None--', $MODULE_NAME)}</option>
							{else}
								{foreach from=$ACCOUNTSLIST item=item key=key}
									<option value="{$item['user_id']}" {if $USER == $item['user_id']}selected{/if}>{$item['username']}</option>
								{/foreach}
							{/if}
						</select>
					</div>
				</td>
				<td class="widgeticons span1" align="right">
					<div class="box pull-right">
						{if !$WIDGET->isDefault()}
							<a name="dclose" class="widget" data-url="{$WIDGET->getDeleteUrl()}">
								<i class="icon-remove" hspace="2" border="0" align="absmiddle" title="{vtranslate('LBL_REMOVE')}" alt="{vtranslate('LBL_REMOVE')}"></i>
							</a>
						{/if}
					</div>
				</td>
			</tr>
			<tr>
				<td colspan= "3" class="refresh" align="center">
					<span></span>
				</td>
			</tr>
		</tbody>
	</table>

</div>
<div class="dashboardWidgetContent">
	{include file="dashboards/MailsListContents.tpl"|@vtemplate_path:$MODULE_NAME}
</div>