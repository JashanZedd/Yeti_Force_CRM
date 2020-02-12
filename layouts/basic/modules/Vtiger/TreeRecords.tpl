{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<table class="table tableBorderHeadBody listViewEntriesTable medium  js-fixed-thead">
		<thead>
			<tr>
				{foreach item=HEADER from=$HEADERS}
					<th class="noWrap">
						{\App\Language::translate($HEADER->get('label'), $MODULE)}
					</th>
				{/foreach}
			</tr>
		</thead>
		<tbody>
			{foreach item=ENTRY from=$ENTRIES name=listview}
				<tr>
					{foreach item=HEADER from=$HEADERS}
						{assign var=HEADERNAME value=$HEADER->get('name')}
						<td>
							{if $HEADER->isNameField() eq true && $ENTRY->isViewable()}
								<a {if $HEADER->isNameField() eq true}class="modCT_{$MODULE}"{/if} href="{$ENTRY->getDetailViewUrl()}">
									{$ENTRY->getListViewDisplayValue($HEADERNAME)}
								</a>
							{else}
								{$ENTRY->getListViewDisplayValue($HEADERNAME)}
							{/if}
						</td>
					{/foreach}
				</tr>
			{/foreach}
		</tbody>
	</table>
{/strip}
