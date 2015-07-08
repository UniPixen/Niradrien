<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h2 class="page-title">{$lang.newsletter}</h2>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
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
			</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.name}</th>
						<th>{$lang.readed}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.name}</td>
							<td>{$d.readed}</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=view&amp;id={$d.id}&amp;p={$smarty.get.p}" title="{$lang.view}">
									<img src="{$data_server}admin/images/icons/16x16/view.png" />
									{$lang.view}
								</a>
							</td>					
						</tr>
					{/foreach}
				</tbody>
			</table> 	
		{else}
			{$lang.no_records}
		{/if}
		<div class="form-submit" style="margin-top: 30px;">
			<button onclick="window.location='?m={$smarty.get.m}&c=add';" type="button" class="btn btn-big-shadow" style="width: 250px; height: 45px;">{$lang.add_new}</button>
		</div>
	</div>
</section>