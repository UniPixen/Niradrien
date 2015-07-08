<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$pdata.name}</h1>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
				<a href="?m={$smarty.get.m}&amp;c=list">{$title}</a> \
			</div>
		</div>
	</div>
</section>

<section id="conteneur">
	<div class="container">
		{if $paging != ""}
			<div class="row page-controls">
				<div class="pagination-fichiers span12">
					{$paging}
				</div>
			</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.attribute}</th>
						<th>{$lang.attribute} {$lang.english}</th>
						<th>{$lang.tooltip}</th>
						<th>{$lang.visible}</th>
						<th>{$lang.sort}</th>
						<th width="150"></th>
					</tr>
				</thead>

				<tbody>
				{foreach from=$data item=d}
					<tr id="row{$d.id}">
						<td>{$d.name}</td>
						<td>{$d.name_en}</td>
						<td>{$d.tooltip}</td>
						<td>
							{if $d.visible == 'true'}
								<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
							{else}
								<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
							{/if}
						</td>
						<td>
							<a href="?m={$smarty.get.m}&c=attr&id={$smarty.get.id}&down={$d.id}&p={$smarty.get.p}" title="{$lang.up}">
								<img src="{$data_server}admin/images/icons/16x16/up.png" />
							</a>
							<a href="?m={$smarty.get.m}&c=attr&id={$smarty.get.id}&up={$d.id}&p={$smarty.get.p}" title="{$lang.down}">
								<img src="{$data_server}admin/images/icons/16x16/download1.png" />
							</a>				
						</td>								
						<td>
							<a href="?m={$smarty.get.m}&c=editAttr&id={$smarty.get.id}&fid={$d.id}" title="{$lang.edit}">
								<img src="{$data_server}admin/images/icons/16x16/edit.png" />
								{$lang.edit}
							</a><br />
							<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteAttr':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
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
			<button onclick="window.location='?m={$smarty.get.m}&c=addAttr&id={$smarty.get.id}';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 200px;">{$lang.add}</button>
		</div>
	</div>
</section>