<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
            	<h1 class="page-title">{$lang.payout_requests}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
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
			</div><br /><br />
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.user}</th>
						<th>{$lang.sales_earnings}</th>
						<th>{$lang.amount}</th>
						<th>{$lang.payment_method}</th>
						<th>{$lang.email}</th>
						<th>{$lang.request_date}</th>
						<th>{$lang.paid}</th>
						<th width="150"></th>
					</tr>
				</thead>

				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>{$members[$d.member_id].username}</td>
							<td>{$members[$d.member_id].earning|string_format:"%.2f"} {$currency.symbol}</td>
							<td>
								{if !is_numeric($d.amount)}
									{$d.amount}
								{else}
									{$d.amount|string_format:"%.2f"} {$currency.symbol}
								{/if}
							</td>
							<td>
								{if $d.method == 'paypal'}
									<img src="{$data_server}uploads/payments/paypal.svg" alt="PayPal" style="height: 20px;" />
								{else}
									{$d.method}
								{/if}
							</td>
							<td>{$d.payment_email|nl2br}</td>
							<td>{$d.datetime|date_format:"%d %b %Y"}</td>
							<td>
								{if $d.paid == 'true'}
									<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />

									{$d.paid_datetime|date_format:"%d %b %Y"}
								{else}
									<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								{if $d.paid == 'false'}
									<a href="?m={$smarty.get.m}&c=pay&id={$d.id}" title="{$lang.payout}">
										<img src="{$data_server}admin/images/icons/16x16/edit.png" />
										{$lang.payout}
									</a><br />
									<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteWithdraw':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
										<img src="{$data_server}admin/images/icons/16x16/delete.png" />
										{$lang.delete}
									</a>
								{/if}
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