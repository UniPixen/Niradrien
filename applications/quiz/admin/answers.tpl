<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$pdata.name}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$lang.quiz}</a> \
                <a href="?m={$smarty.get.m}&amp;c=list">{$title}</a> \
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
						<th>Answers</th>
						<th>{$lang.right}</th>
						<th width="150"></th>
					</tr>
				</thead>

				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.name}</td>
							<td>
								{if $d.right == 'true'}
									<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
								{else}
									<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
								{/if}
							</td>
							<td>
								<a href="?m={$smarty.get.m}&c=editAnswer&id={$smarty.get.id}&fid={$d.id}" title="{$lang.edit}">
									<img src="{$data_server}admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'deleteAnswer':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
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
			<button onclick="window.location='?m={$smarty.get.m}&c=addAnswer&id={$smarty.get.id}';" type="button" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.add_new}</button>
		</div>
	</div>
</section>