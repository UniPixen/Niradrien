<section class="titre-profil admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$lang.payment_rates}</h1>
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
		{if !empty($paging)}
			<div class="page-controls">
				{$paging}
			</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.percent}</th>
						<th>{$lang.from}</th>
						<th>{$lang.to}</th>
						<th width="150"></th>
					</tr>
				</thead>

				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.percent}%</td>
							<td>{$d.from|string_format:"%.0f"} &euro;</td>
							<td>{$d.to|string_format:"%.0f"} &euro;</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=edit&amp;fid={$d.id}" title="{$lang.edit}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
									<img src="{$data_server}/admin/images/icons/16x16/delete.png" />
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
		<div style="margin-top: 30px;">
			<button onclick="window.location='?m={$smarty.get.m}&amp;c=add';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add_new}</button>
		</div>
	</div>
</section>