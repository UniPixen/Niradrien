<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{if isset($pdata)}Subp{else}P{/if}ages</h1>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
				{if isset($pdata)}
					<a href="?m={$smarty.get.m}&c=list">Pages</a> \
					<a href="?m={$smarty.get.m}&c=list&sub_of={$pdata.sub_of}">{$pdata.name}</a> \
				{/if}
			</div>
		</div>
	</div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		{if $paging !=""}
			<div class="page-controls">
				{$paging}
			</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.name}</th>
						<th>{$lang.key}</th>
						<th>{$lang.in_menu}</th>
						<th>{$lang.visible}</th>
						<th>{$lang.sort}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
				{foreach from=$data item=d}
					<tr>
						<td>{$d.name}</td>
						<td>{$d.key}</td>
						<td>
							{if $d.menu == 'true'}
								<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
							{else}
								<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
							{/if}
						</td>
						<td>
							{if $d.visible == 'true'}
								<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
							{else}
								<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
							{/if}
						</td>
						<td>
							<a href="?m={$smarty.get.m}&c=list&down={$d.id}&p={$smarty.get.p}&sub_of={$smarty.get.sub_of}" title="{$lang.up}">
								<img src="{$data_server}admin/images/icons/16x16/up.png" />
							</a>
							<a href="?m={$smarty.get.m}&c=list&up={$d.id}&p={$smarty.get.p}&sub_of={$smarty.get.sub_of}" title="{$lang.down}">
								<img src="{$data_server}admin/images/icons/16x16/download1.png" />
							</a>
						</td>
						<td>
							{if !isset($smarty.get.sub_of) || !is_numeric($smarty.get.sub_of) || $smarty.get.sub_of == '0'}
								<a href="?m={$smarty.get.m}&c=list&sub_of={$d.id}" title="{$lang.subpages}">
									<img src="{$data_server}admin/images/icons/16x16/pages.png" />
									{$lang.subpages}
								</a>
								<br />
							{/if}
							<a href="?m={$smarty.get.m}&c=edit&id={$d.id}&p={$smarty.get.p}&sub_of={$smarty.get.sub_of}" title="{$lang.edit}">
								<img src="{$data_server}admin/images/icons/16x16/edit.png" />
								{$lang.edit}
							</a>
							<br />
							<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
								<img src="{$data_server}admin/images/icons/16x16/delete.png" />
								{$lang.delete}
							</a>
						</td>
					</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			{$lang.no_records}
		{/if}

		<div class="form-submit" style="margin-top: 30px;">
			<button onclick="window.location='?m={$smarty.get.m}&c=add&sub_of={$smarty.get.sub_of}';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add}</button>
		</div>
	</div>
</section>