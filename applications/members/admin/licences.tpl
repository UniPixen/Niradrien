<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
            	<h1 class="page-title">{$lang.licenses} {$lang.of} {$members[$smarty.get.id].username}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/">{$lang.home}</a> \
                <a href="/admin/?m=members&amp;c=list">{$lang.users}</a> \
                <a href="/admin/?m=members&amp;c=edit&amp;id={$smarty.get.id}">{$members[$smarty.get.id].username}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
    	<div class="row">
			<div class="span12">
			{if $data}
				<table class="table-grey">
					<thead>
						<tr>
							<th>{$lang.product}</th>
							<th>{$lang.seller}</th>
							<th>{$lang.paid_price}</th>
							<th>{$lang.payment_date}</th>
							<th>{$lang.licence_type}</th>
							<th>{$lang.download_key}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$data item=d}
							{if $d.extended == 'true'}
								{assign var='licence_type' value={$lang.extended_license}}
							{else}
								{assign var='licence_type' value={$lang.regular_licence}}
							{/if}
							<tr>
								<td><a href="/product/{$d.product_id}/{$d.product_name|url}">{$d.product_name}</a></td>
								<td><a href="/member/{$members[$d.owner_id].username}">{$members[$d.owner_id].username}</a></td>
								<td>{$d.paid_price|string_format:"%.2f"} {$currency.symbol}</td>
								<td>{$d.paid_datetime|date_format:"%e %B %Y <small>à %H:%M</small>"}</td>
								<td>{$licence_type}</td>
								<td>{$d.code_achat}</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
				{else}
					{$lang.no_records}
				{/if}
			</div>
		</div>
	</div>
</section>