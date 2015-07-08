<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">
					{$lang.products}
					<span>{$foundRows} {$lang.found_rows|lower}</span>
				</h1>
			</div>
			<div class="span5" id="breadcrumbs">
				<a href="/admin">{$lang.home}</a> \
				<a href="/admin/?m=products&amp;c=list">{$lang.files}</a> \
			</div>
		</div>
	</div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
	<div class="container">
		{if $paging != ""}
			<div class="page-controls">
				<form class="sort-control" method="post" style="width: 350px;" action="?m={$smarty.get.m}&amp;c={$smarty.get.c}">
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
						<th>
							<a href="?m={$smarty.get.m}&amp;c={$smarty.get.c}&amp;p={$smarty.get.p}&amp;q={$smarty.get.q}&amp;order=name&amp;dir={$orderDir}&amp;member={$smarty.get.member}" title="">{$lang.product_name}</a>
						</th>
						<th>
							{$lang.seller}
						</th>
						<th>
							<a href="?m={$smarty.get.m}&amp;c={$smarty.get.c}&amp;p={$smarty.get.p}&amp;q={$smarty.get.q}&amp;order=price&amp;dir={$orderDir}&amp;member={$smarty.get.member}" title="">{$lang.price}</a>
						</th>
						<th>
							<a href="?m={$smarty.get.m}&amp;c={$smarty.get.c}&amp;p={$smarty.get.p}&amp;q={$smarty.get.q}&amp;order=sales&amp;dir={$orderDir}&amp;member={$smarty.get.member}" title="">{$lang.sales}</a>
						</th>
						<th>
							<a href="?m={$smarty.get.m}&amp;c={$smarty.get.c}&amp;p={$smarty.get.p}&amp;q={$smarty.get.q}&amp;order=earning&amp;dir={$orderDir}&amp;member={$smarty.get.member}" title="">{$lang.win}</a>
						</th>
						<th width="170"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>
								{$d.name}
								{if $d.status == 'active'}
									<span style="display: inline-block; text-indent: -9999px; margin-left: 10px; width: 7px; border-radius: 7px; height: 7px; line-height: 10px; background: #27ae60;" title="En ligne">En ligne</span>
								{/if}
								{if $d.status == 'queue'}
									<span style="display: inline-block; text-indent: -9999px; margin-left: 10px; width: 7px; border-radius: 7px; height: 7px; line-height: 10px; background: #f1c40f;" title="En attente d'approbation">En attente d'approbation</span>
								{/if}
								{if $d.status == 'unapproved'}
									<span style="display: inline-block; text-indent: -9999px; margin-left: 10px; width: 7px; border-radius: 7px; height: 7px; line-height: 10px; background: #e67e22;" title="Non accepté">Non accepté</span>
								{/if}
								{if $d.status == 'deleted'}
									<span style="display: inline-block; text-indent: -9999px; margin-left: 10px; width: 7px; border-radius: 7px; height: 7px; line-height: 10px; background: #e74c3c;" title="Supprimé">Supprimé</span>
								{/if}
							</td>
							<td>{$members[$d.member_id].username}</td>
							<td>{$d.price|string_format:"%.2f"} {$currency.symbol}</td>
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