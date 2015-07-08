<section id="titre-page" class="page-header">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$lang.history}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/">{$lang.home}</a> \
				<a href="/members/{$smarty.session.member.username}">{$lang.my_account}</a> \
			</div>
		</div>
	</div>
</section>

<div id="menu-page">
    {include file="$root_path/applications/members/templates/tabsy.tpl"}
    <div id="menu-haut" class="container">
	    <div class="row">
			<h3 class="titre-centre">
				{if $nav.prev.show == 'true'}
					<a href="/account/history/?month={$nav.prev.month}&amp;year={$nav.prev.year}" class="slider-control slider-prev"><i class="hd-arrow-left"></i></a>
				{else}
					<span class="slider-control slider-prev-disabled"><i class="hd-arrow-left"></i></span>
				{/if}

				{$lang.history_for} {$lang.monthArr[$smarty.get.month]} {$smarty.get.year}

				{if $nav.next.show == 'true'}
					<a href="/account/history/?month={$nav.next.month}&amp;year={$nav.next.year}" class="slider-control slider-next"><i class="hd-arrow-right"></i></a>
				{else}
					<span class="slider-control slider-next-disabled"><i class="hd-arrow-right"></i></span>
				{/if}
			</h3>
		</div>
    </div>
</div>

<div id="conteneur">
	<div class="container">
		<div id="history" class="row">
			<table class="tableau-simple">
				<thead>
					<tr>
						<th>{$lang.date}</th>
						<th>{$lang.type}</th>
						<th>{$lang.amount}</th>
						<th>{$lang.details}</th>
					</tr>
				</thead>
				<tbody>
					{if $history}
						{foreach from=$history item=s}
							<tr>
								<td>{$s.datetime|date_format:"%d %B %Y"}</td>
								<td>
						        	<strong>
						        		{if $s.type == 'deposit'}
						        			{$lang.deposit}
						        		{elseif $s.type == 'withdraw'}
						        			{$lang.withdraw_money}
						        		{elseif $s.type == 'order' && $s.owner_id == $smarty.session.member.member_id}
						        			{$lang.receive_money}
						        		{else}
						        			{$lang.purchase_money}
						        		{/if}
						        	</strong>
						        </td>
						        <td>
						        	{if $s.type == 'deposit'}
						        		<div class="more">{$s.price|string_format:"%.2f"} {$currency.symbol}</div>
						        	{elseif $s.type == 'withdraw'}
						        		<div class="less">- {$s.price|string_format:"%.2f"} {$currency.symbol}</div>
						        	{elseif $s.type == 'order' && $s.owner_id == $smarty.session.member.member_id}
						        		<div class="more">{$s.receive|string_format:"%.2f"} {$currency.symbol}</div>
						        	{else}
						        		<div class="less">- {$s.price|string_format:"%.2f"} {$currency.symbol}</div>
						        	{/if}
						        </td>
						        <td>
						        	{if $s.type == 'deposit'}
						        		{$lang.deposit_money}
						        	{elseif $s.type == 'withdraw'}
						        		{$lang.earning_money}
						        	{elseif $s.type == 'order' && $s.owner_id == $smarty.session.member.member_id}
						        		{if $s.referal == 'buy'}
						        	{$s.product_name} {$lang.sold_product}
						        		{else}
						        	{$lang.referal_money}
						        		{/if}
						        	{else}
						        		{$s.product_name}
						        	{/if}
						        </td>
						    </tr>
						{/foreach}
					{else}
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					{/if}
				</tbody>
			</table>
		</div>
		<div id="history-download" class="row">
			<a href="{$download_csv_info|escape}" class="btn btn-big-shadow">
				{$lang.download}
				<i class="hd-reward"></i>
			</a>
		</div>
	</div>
</div>