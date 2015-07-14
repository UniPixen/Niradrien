<section id="titre-page" class="page-header">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$lang.earnings}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/">{$lang.home}</a> \
				<a href="/members/{$smarty.session.member.username}/">{$lang.my_account}</a> \
			</div>
		</div>
	</div>
</section>

<div id="commission">
    <div id="commission-container">
        <div class="btn btn btn-big-shadow btn-grey">
            {$lang.commission} : {$commission.percent} &#37;
        </div>
    </div>
</div>

<div id="menu-page">
	{include file="$root_path/applications/members/templates/tabsy.tpl"}
	{include file="$root_path/applications/members/templates/author-tab.tpl"}
</div>

<section id="earnings">
	<div class="container">
		<div class="row">
			<h3 class="titre-centre">{$lang.sales}</h3>
		</div>

		<div class="row">
			<div class="stats-box span4">
				{$lang.sales_on} {$lang.monthArr.{$currentMonth}|@lower} :
				<h4>{$monthEarning|string_format:"%.2f"} {$currency.symbol}</h4>
				<span>
					{$monthSales}
					{if $monthSales <= 2}
						{$lang.sold_product|@lower}
					{else}
						{$lang.sold_products|@lower}
					{/if}
				</span>
			</div>
			<div class="stats-box span4">
				{$lang.sales_amount} :
				<h4>{$member.earning|string_format:"%.2f"} {$currency.symbol}</h4>
				<span><a href="/account/withdraw">{$lang.ask_a_withdraw}</a></span>
			</div>
			<div class="stats-box span4">
				{$lang.total_earning} :
				<h4>{$member.total|string_format:"%.2f"} {$currency.symbol}</h4>
				<span>{$lang.on_a_total_of} {$member.sales} {$lang.sales|@lower}</span>
			</div>
		</div>

		<div id="sales-chart-header" class="row">
			<div id="type-earnings" class="span6">
				{$earningsBreadcrumb}
			</div>
			<div id="sales-legend" class="span6">
				<span class="stroke"></span> {$lang.withdraw} ({$lang.in2|lower} {$currency.symbol})
			</div>
		</div>

		<div id="sales-chart" class="row">
			<canvas id="canvas" height="300" width="964"></canvas>
			
			{literal}
				<script>
					var randomScalingFactor = function(){ return Math.round(Math.random() * 100)};
					
					var lineChartData = {
						labels : [
							{/literal}{$chartLabel}{literal}
						],
						datasets : [
							{
								fillColor : "rgba(175,210,61,0.4)",
								strokeColor : "#afd23d",
								pointColor : "#afd23d",
								pointStrokeColor : "#fff",
								pointHighlightFill : "#fff",
								pointHighlightStroke : "#afbbcd",
								{/literal}{if $sales}data : {$jsonData}{/if}{literal}
							}
						]
					}

					window.onload = function(){
						var ctx = document.getElementById("canvas").getContext("2d");

						window.myLine = new Chart(ctx).Line(lineChartData, {
							responsive: true
						});
					}
				</script>
			{/literal}
		</div>

		{if $sales}
			<div id="sales-table" class="row">
				<table class="table-grey">
					<thead>
						<tr>
							<th>{$lang.date}</th>
							<th>{$lang.sales_count}</th>
							<th>{$lang.earnings}</th>
						</tr>
					</thead>
					<tbody>
						{$totalBuy = 0}
						{$totalEarning = 0}
 						
 						{foreach from=$sales item=y key=year}
							{foreach from=$y item=m key=month}
								{$totalBuy = $totalBuy + {$m.buy}}
								{$totalEarning = $totalEarning + {$m.total}}
							{/foreach}
						{/foreach}

						{foreach from=$ordersData item=o key=day name=foo}
							<tr>
								<td>{$date[{$smarty.foreach.foo.iteration - 1}]}</td>
								<td>{$o.sale}</td>
								<td>{$o.earning|string_format:"%.2f"} {$currency.symbol}</td>
							</tr>
						{/foreach}
					</tbody>
					<tfoot>
						<tr>
							<td>{$lang.total}</td>
							<td>{$totalBuy}</td>
							<td>{$totalEarning|string_format:"%.2f"} {$currency.symbol}</td>
						</tr>
					</tfoot>
				</table>
			</div>
		{else}
			<div id="no-sales" class="row">
				<div class="span12">{$lang.sold_nothing_period}</div>
			</div>
		{/if}
	</div>
</section>

{*
<section id="parrainage-earnings">
	<div class="container">
		<div class="row">
			<h3 class="titre-centre">{$lang.referals}</h3>
		</div>
		<div class="row">
			{if $referals}
				<table id="referrals_table" class="general_table" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<td>{$lang.month}</td>
							<td>{$lang.buy_products}</td>
							<td>{$lang.deposit}</td>
							<td>{$lang.earnings}</td>
						</tr>
					</thead>
					<tbody>
						{foreach from=$referals item=r key=year}
							{foreach from=$r item=rr key=month}
								<tr class="{$year}">
									<td><strong>{$lang.monthArr[$month]} {$year}</strong></td>
									<td>{$rr.buy}</td>
									<td>{$rr.deposit}</td>
									<td class="earningsVal">{$rr.total|string_format:"%.2f"} {$currency.symbol}</td>
								</tr>
							{/foreach}
						{/foreach}
					</tbody>
					<tfoot>
						<tr>
							<td>{$lang.total_referal_earning}:</td>
							<td></td>
							<td></td>
							<td class="earningsTotal">{$earnings.referal|string_format:"%.2f"} {$currency.symbol}</td>
						</tr>
					</tfoot>
				</table>
			{else}
				{$lang.no_records}
			{/if}
		</div>
	</div>
</section>
*}

<script src="{$data_server}js/chart.js"></script>
