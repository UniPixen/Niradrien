<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.placed_in_front_products}</h1>
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
		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.product_name}</th>
						<th>{$lang.seller}</th>
						<th>{$lang.sales}</th>
						<th>{$lang.win}</th>
						<th width="170"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>{$d.name}</td>
							<td>{$members[$d.member_id].username}</td>
							<td>{$d.sales}</td>
							<td>{$d.earning|string_format:"%.2f"} {$currency.symbol}</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=comments&amp;id={$d.id}" title="{$lang.comments}">
									<img src="{$data_server}/admin/images/icons/16x16/icon-comments.png" />
									{$d.comments} {$lang.comments|@lower}
								</a><br/>
								<a href="?m={$smarty.get.m}&amp;c=edit&amp;id={$d.id}&amp;p={$smarty.get.p}" title="{$lang.edit_product}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" /> {$lang.edit_product}
								</a><br/>
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
									<img src="{$data_server}/admin/images/icons/16x16/delete.png" />
									{$lang.delete_product}
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