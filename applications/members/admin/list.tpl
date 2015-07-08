<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.members}</h1>
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
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=name&dir={$orderDir}">#</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=name&dir={$orderDir}">{$lang.username}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=money&dir={$orderDir}">{$lang.current_balance}</a></th>
						<th>{$lang.commission}</th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=products&dir={$orderDir}">{$lang.products}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=sales&dir={$orderDir}">{$lang.sales}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=purchases&dir={$orderDir}">{$lang.purchases}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=sold&dir={$orderDir}">{$lang.sales_earnings}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=referals&dir={$orderDir}">{$lang.referals}</a></th>
						<th><a href="?m={$smarty.get.m}&c={$smarty.get.c}&p={$smarty.get.p}&q={$smarty.get.q}&order=referal_money&dir={$orderDir}">{$lang.referal_earnings}</a></th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.member_id}">
							<td>{$d.member_id}</td>
							<td>{$d.username}</td>
							<td class="ta-right">{$d.total|string_format:"%.2f"} {$currency.symbol}</td>
							<td class="ta-right">{$d.commission} %</td>
							<td><a href="?m=products&c=list&member={$d.member_id}" title="">{$d.products}</a></td>
							<td>{$d.sales}</td>
							<td>{$d.buy}</td>
							<td class="ta-right">{$d.sold|string_format:"%.2f"} {$currency.symbol}</td>
							<td>{$d.referals}</td>
							<td class="ta-right">{$d.referal_money|string_format:"%.2f"} {$currency.symbol}</td>
							<td>
								<a href="?m={$smarty.get.m}&c=balance&id={$d.member_id}" title="{$lang.current_balance}">
									<img src="{$data_server}/admin/images/icons/16x16/coins.png" />
									{$lang.edit_balance}
								</a><br />
								<a href="?m={$smarty.get.m}&c=edit&id={$d.member_id}" title="{$lang.edit}">
									<img src="{$data_server}admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteUser':true,'id':'{/literal}{$d.member_id}{literal}'}{/literal},'deleteRow');">
									<img src="{$data_server}admin/images/icons/16x16/delete.png" />
									{$lang.delete}
								</a><br />
								<a href="?m={$smarty.get.m}&c=licenses&id={$d.member_id}" title="{$lang.licenses}">
									<img src="{$data_server}admin/images/icons/16x16/answers.png" />
									{$lang.licenses}
								</a>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		{else}
			{$lang.no_records}
		{/if}

		{if $paging != ""}
			<div class="page-controls">
				<form class="sort-control" method="post" style="width: 350px;" action="?m={$smarty.get.m}&amp;c={$smarty.get.c}">
					<input type="text" id="q" name="q" value="{$lang.search}..." onfocus="this.value=='{$lang.search}...'?this.value='':false;" onblur="this.value==''?this.value='{$lang.search}...':false;">
					<button type="submit" class="image-button search no-margin">{$lang.search}</button>
				</form>
				{$paging}
			</div>
		{/if}
	</div>
</section>