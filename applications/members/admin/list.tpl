<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">
					{$lang.members}
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
				<form method="GET" action="?m={$smarty.get.m}&amp;c={$smarty.get.c}">
					<input type="hidden" name="m" value="{$smarty.get.m}" />
					<input type="hidden" name="c" value="{$smarty.get.c}" />
					<input type="text" name="q" value="" placeholder="{$lang.search}..." style="width: 300px;" />
					<button type="submit" class="btn btn-big-shadow" style="height: 40px; width: 170px; box-shadow: 0 3px 0 #92b329; margin-left: 15px;">{$lang.search}</button>
				</form>
			</div>
			{if !empty($paging)}
				<div class="span6">
					<div class="pagination-fichiers">{$paging}</div>
				</div>
			{/if}
		</div>

		<div class="row">
			<div class="span12">
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
			</div>
		</div>

		<div class="row" style="margin-top: 30px;">
			{if !empty($paging)}
				<div class="span12">
					<div class="pagination-fichiers">{$paging}</div>
				</div>
			{/if}
		</div>
	</div>
</section>
