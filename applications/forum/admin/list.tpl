<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.topics}</h1>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
				<a href="/admin/?m=forum&amp;c=list">{$lang.forum}</a> \
			</div>
		</div>
	</div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		{if $paging != ""}
			<div class="page-controls">
				<form class="sort-control" method="post" style="width: 350px;" action="?m={$smarty.get.m}&c={$smarty.get.c}">
					<input type="text" id="q" name="q" value="{$lang.search}..." onfocus="this.value=='{$lang.search}...'?this.value='':false;" onblur="this.value==''?this.value='{$lang.search}...':false;">
					<button type="submit" class="image-button search no-margin">{$lang.search}</button>
				</form>
				{$paging}
			</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>
							{$lang.name}
						</th>
						<th>
							{$lang.name} {$lang.english}
						</th>
						<th>
							{$lang.threads}
						</th>
						<th>
							{$lang.sort}
						</th>
						<th>
							{$lang.visible}
						</th>
						<th width="170"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>{$d.name}</td>
							<td>{$d.name_en}</td>
							<td>
								{$d.nombre_threads}
							</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=list&amp;down={$d.id}&amp;p={$smarty.get.p}" title="{$lang.up}">
									<img src="{$data_server}admin/images/icons/16x16/up.png" />
								</a>
								<a href="?m={$smarty.get.m}&amp;c=list&amp;up={$d.id}&amp;p={$smarty.get.p}" title="{$lang.down}">
									<img src="{$data_server}admin/images/icons/16x16/download1.png" />
								</a>				
							</td>
							<td>
								{if $d.visible == 'true'}
									<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=edit&amp;id={$d.id}&amp;p={$smarty.get.p}" title="{$lang.edit}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
									<img src="{$data_server}/admin/images/icons/16x16/delete.png" />
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
			<button onclick="window.location='?m={$smarty.get.m}&c=add';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 270px;">
				{$lang.add_new}
			</button>
		</div>
	</div>
</section>