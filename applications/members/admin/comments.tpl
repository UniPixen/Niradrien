<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$title}</h1>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
				<a href="/admin/?m=members&amp;c=list">{$lang.users}</a> \
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
						<th>{$lang.comment}</th>
						<th>{$lang.report_reason}</th>
						<th>{$lang.product}</th>
						<th>{$lang.reported_by}</th>
						<th width="150"></th>
					</tr>
				</thead>

				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.comment|nl2br}</td>
							<td>{$d.report_reason}</td>
							<td><a href="/product/{$d.product_id}/{$d.product_name|url}" title="" target="_blank">{$d.product_name}</a></td>
							<td><a href="/member/{$members[$d.report_by].username}">{$members[$d.report_by].username}</a></td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=comments&amp;check={$d.id}" title="{$lang.cancel}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
									{$lang.cancel}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteComment':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
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
	</div>
</section>