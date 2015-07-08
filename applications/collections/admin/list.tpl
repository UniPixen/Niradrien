<section class="titre-profil admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h1 class="page-title">{$lang.collections}</h1>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.collections}</a> \
            </div>
        </div>
    </div>
</section>

{include file="$root_path/applications/admin/admin_tabsy.tpl"}

<section id="conteneur">
    <div class="container">
		{if $paging !=""}
			<div class="page-controls">{$paging}</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.name}</th>
						<th>{$lang.user}</th>
						<th>{$lang.products}</th>
						<th>{$lang.public_visible}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.id}">
							<td>{$d.name}</td>
							<td><a href="/member/{$members[$d.member_id].username}" target="_blank" title="{$lang.view}">{$members[$d.member_id].username}</a></td>
							<td>{$d.products}</td>
							<td>
								{if $d.public == 'true'}
									<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								<a href="/collections/view/{$d.id}" target="_blank" title="{$lang.view}">
									<img src="{$data_server}admin/images/icons/16x16/view.png" />
									{$lang.view}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
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