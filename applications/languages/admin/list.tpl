<section id="titre-page" class="titre-page admin">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$title}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/admin">{$lang.home}</a> \
			</div>
		</div>
	</div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		<div class="row">
			<div class="span9">
				{if $paging != ""}
					<div class="page-controls">
						{$paging}
					</div>
				{/if}

				{if is_array($data)}
					<table class="table-grey">
						<thead>
							<tr>
								<th>{$lang.name}</th>
								<th>{$lang.code}</th>
								<th>Local</th>
								<th>Local Territory</th>
								<th>Drapeau</th>
								<th>Visible</th>
								<th>{$lang.sort}</th>
								<th width="150"></th>
							</tr>
						</thead>

						<tbody>
						{foreach from=$data item=d}
							<tr id="row{$d.id}">
								<td>{$d.name}</td>
								<td>
									{$d.code}
								</td>
								<td>
									{$d.locale}
								</td>
								<td>
									{$d.locale_territory}
								</td>
								<td>
									{if $d.flag != ''}
										<img src="{$data_server}uploads/languages/{$d.flag}" alt="{$d.name}" style="width: 20px;" />
									{/if}
								</td>
								<td>
									{if $d.visible == '1'}
										<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
									{else}
										<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
									{/if}
								</td>
								<td>
									<a href="?m={$smarty.get.m}&c=list&down={$d.id}&p={$smarty.get.p}" title="{$lang.up}">
										<img src="{$data_server}admin/images/icons/16x16/up.png" />
									</a>
									<a href="?m={$smarty.get.m}&c=list&up={$d.id}&p={$smarty.get.p}" title="{$lang.down}">
										<img src="{$data_server}admin/images/icons/16x16/download1.png" />
									</a>
								</td>
								<td>
									<a href="?m={$smarty.get.m}&c=edit&fid={$d.id}" title="{$lang.edit}">
										<img src="{$data_server}admin/images/icons/16x16/edit.png" />
										{$lang.edit}
									</a><br />
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

				<button onclick="window.location='?m={$smarty.get.m}&c=add';" type="button" class="btn btn-big-shadow btn-middle" style="margin-top: 30px;">{$lang.add}</button>
			</div>
		</div>
	</div>
</section>