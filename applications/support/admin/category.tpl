<section class="titre-page admin" id="titre-page">
    <div class="container">
        <div class="row">
            <div class="span7" id="titre">
                <h2 class="page-title">{$title}</h2>
            </div>
            <div class="span5" id="breadcrumbs">
                <a href="/admin">{$lang.home}</a> \
            <a href="?m={$smarty.get.m}&c=list&p={$smarty.get.p}">{$lang.support}</a> \
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
						<th>{$lang.english_name}</th>
						<th>{$lang.visible}</th>
						<th>{$lang.sort}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.name}</td>
							<td>{$d.name_en}</td>
							<td>
								{if $d.visible == 'true'}
									<img src="{$data_server}/admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}/admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								<a href="?m={$smarty.get.m}&c=categories&down={$d.id}&p={$smarty.get.p}&sub_of={$smarty.get.sub_of}" title="{$lang.up}">
									<img src="{$data_server}admin/images/icons/16x16/up.png" />
								</a>
								<a href="?m={$smarty.get.m}&c=categories&up={$d.id}&p={$smarty.get.p}&sub_of={$smarty.get.sub_of}" title="{$lang.down}">
									<img src="{$data_server}admin/images/icons/16x16/download1.png" />
								</a>				
							</td>								
							<td>
								<a href="?m={$smarty.get.m}&c=edit&id={$d.id}&p={$smarty.get.p}" title="{$lang.edit}">
									<img src="{$data_server}admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteCategory':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
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

		<div class="form-submit">
			<button onclick="window.location='?m={$smarty.get.m}&c=add';" type="button" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.add_new}</button>
		</div>
	</div>
</section>