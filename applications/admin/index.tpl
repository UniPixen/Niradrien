<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.dashboard}</h1>
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
			<div class="span8">
				<div id="finance_chart"></div><br />
				{if $updatedProducts}
					<div id="sales_chart"></div>
				{/if}
			</div>

			<div class="span4">
				{if $supportTicket}
					<div style="margin-bottom: 30px;">
						<h2>{$lang.support_tickets}</h2>
						<table class="table-grey">
							<tbody>
								{foreach from=$supportTicket item=i}
									<tr>
										<td>
											<a href="?m=support&amp;c=view&amp;id={$i.id}">
												{$lang.message}
											</a>
											{$lang.from|@lower} {$i.name}
										</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				{/if}

				{if $newProducts}
					<div style="margin-bottom: 30px;">
						<h2>{$lang.products_for_approve}</h2>
						<table class="table-grey">
							<tbody>
								{foreach from=$newProducts item=i}
									<tr>
										<td>
											<a href="?m=product&amp;c=queue_view&amp;id={$i.id}">{$i.name}</a>
										</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				{/if}

				{if $updatedProducts}
					<div style="margin-bottom: 30px;">
						<h2>{$lang.queue_update}</h2>
							<table class="table-grey">
							<tbody>
								{foreach from=$updatedProducts item=i}
									<tr>
										<td>
											<a href="?m=product&amp;c=queue_view_update&amp;id={$i.id}">{$i.name}</a>
										</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				{/if}

				<div style="margin-bottom: 30px;">
					<h2>{$lang.statistics}</h2>
					<table class="table-grey">
						<tbody>
							<tr>
								<td>Revenu mensuel</td>
								<td></td>
								<td>{$sales.total|string_format:"%.2f"} {$currency.symbol}</td>
							</tr>
							<tr>
								<td>{$lang.members_earnings}</td>
								<td></td>
								<td>{$sales.receive|string_format:"%.2f"} {$currency.symbol}</td>
							</tr>
							<tr>
								<td><strong>{$lang.commission_earnings}</strong></td>
								<td></td>
								<td><strong style="color: {if $sales.win > 0}#27ae60{else}#e74c3c{/if};">{$sales.win|string_format:"%.2f"} {$currency.symbol}</strong></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			{literal}
				<script>
					var finance_chart, sales_chart;
					$(document).ready(function() {
						finance_chart = new Highcharts.Chart({
							credits: {
								enabled: false
							},
							chart: {
								renderTo: 'finance_chart',
								defaultSeriesType: 'area'
							},
							legend: {
								align: 'center',
								verticalAlign: 'top',
								y: 0,
								floating: false,
								borderWidth: 0
							},
							title: {
								text: ""
							},
							xAxis: {
								categories: {/literal}{$days}{literal}
							},
							yAxis: {
								title: {
								text: ''
								},
								min: 0
							},
							plotOptions: {
								area: {
									marker: {
										enabled: false,
										symbol: 'circle',
										radius: 2,
										states: {
											hover: {
												enabled: true
											}
										}
									}
								}
							},
							tooltip: {
								shared: true,
								crosshairs: true,
								valueSuffix: ' {/literal}{$valuta}{literal}',
								headerFormat: '{point.key} {/literal}{$smarty.now|date_format:"%b %Y"}{literal}<br />'
							},
							series: {/literal}{$finance_array}{literal}
						});

						sales_chart = new Highcharts.Chart({
							credits: {
								enabled: false
							},
							legend: {
								align: 'center',
								verticalAlign: 'top',
								y: 0,
								floating: false,
								borderWidth: 0
							},
							chart: {
								renderTo: 'sales_chart',
								defaultSeriesType: 'area'
							},
							title: {
								text: ""
							},
							xAxis: {
								categories: {/literal}{$days}{literal}
							},
							yAxis: {
								title: {
									text: ''
								},
								min: 0
							},
							tooltip: {
								shared: true,
								crosshairs: true,
								headerFormat: '{point.key} {/literal}{$smarty.now|date_format:"%b %Y"}{literal}<br />'
							},
							series: {/literal}{$sales_array}{literal}
						});
					});
				</script>
			{/literal}
			<script src="http://code.highcharts.com/highcharts.js"></script>
		</div>
	</div>
</section>