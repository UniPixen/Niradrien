<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h2 class="page-title">{$lang.support}</h2>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
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
						<th>{$lang.member}</th>
						<th>{$lang.email}</th>
						<th>{$lang.issue}</th>
						<th>{$lang.date}</th>				
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>{$d.name}</td>
							<td>{$d.email}</td>
							<td>{$d.issue}</td>
							<td>{$d.datetime|date_format:"%d %B %Y"}</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=view&amp;id={$d.id}&amp;p={$smarty.get.p}" title="{$lang.view}">
									<img src="{$data_server}admin/images/icons/16x16/view.png" />
									{$lang.view_ticket}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteMail':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow','{$lang.are_you_sure_to_delete} {$d.name|replace:"'":" "|replace:'"':' '} ?');">
									<img src="{$data_server}admin/images/icons/16x16/delete.png" />
									{$lang.delete_ticket}
								</a>
							</td>					
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			{$lang.no_records}
		{/if}
	</div>
</section>