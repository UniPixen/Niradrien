<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">
					{$lang.sales}
					<span>{$foundRows} {$lang.found_rows|lower}</span>
				</h1>
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
		<div class="row">
			<div class="span6">
				<form method="get" action="?m={$smarty.get.m}&amp;c={$smarty.get.c}">
					<input type="hidden" name="m" value="{$smarty.get.m}" />
					<input type="hidden" name="c" value="{$smarty.get.c}" />
					<input type="text" name="purchase_key" value="" placeholder="{$lang.purchase_key}" style="width: 300px;" />
					<button type="submit" class="btn btn-big-shadow" style="height: 40px; width: 170px; box-shadow: 0 3px 0 #92b329; margin-left: 15px;">{$lang.search}</button>
				</form>
			</div>
			{if !empty($paging)}
				<div class="span6">
					<div class="pagination-fichiers">{$paging}</div>
				</div>
			{/if}
		</div>

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=name&dir={$orderDir}">{$lang.product}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=money&dir={$orderDir}">{$lang.buyer}</a></th>
						<th>{$lang.seller}</th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=products&dir={$orderDir}">{$lang.price}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=sales&dir={$orderDir}">{$lang.website_earning}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=sold&dir={$orderDir}">{$lang.author_earning}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=referals&dir={$orderDir}">{$lang.date}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=referal_money&dir={$orderDir}">{$lang.paid}</a></th>
						<th>{$lang.licence}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.product_name}</td>
							<td>{$member[$d.member_id].username}</td>
							<td>{$member[$d.owner_id].username}</td>
							<td><a href="?m=products&c=list&member={$d.member_id}">{$d.price|string_format:"%.2f"} {$currency.symbol}</a></td>
							<td>{$d.price|string_format:"%.2f"} {$currency.symbol}</td>
							<td>{$d.receive|string_format:"%.2f"} {$currency.symbol}</td>
							<td>{$d.datetime|date_format:"%d %B %Y à %Hh%M"}</td>
							<td>
								{if $d.paid == 'true'}
									<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								{if $d.extended == 'true'}
									{$lang.extended_licence}
								{else}
									{$lang.regular_licence}
								{/if}
							</td>
							<td>
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteUser':true,'id':'{/literal}{$d.member_id}{literal}'}{/literal},'deleteRow');">
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
	</div>
</section>