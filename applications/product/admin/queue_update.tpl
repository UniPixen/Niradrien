<section id="titre-page" class="titre-page admin">
    <div class="container">
        <div class="row">
            <div id="titre" class="span7">
                <h1 class="page-title">{$lang.queue_update}</h1>
            </div>
            <div id="breadcrumbs" class="span5">
                <a href="/admin">{$lang.home}</a> \
                <a href="/admin/?m={$smarty.get.m}&c=list">{$lang.products}</a> \
            </div>
        </div>
    </div>
</section>

<section id="conteneur">
	<div class="container">
		<div class="page-controls">{$paging}</div>
		{if is_array($data)}
			<table>
				<thead>
					<tr>
						<th>{$lang.name}</th>
						<th>{$lang.date}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.name}</td>
							<td>{$d.datetime|date_format:"%H:%M %d %b %Y"}</td>
							<td>
								<a href="?m={$smarty.get.m}&c=queue_view_update&id={$d.id}&p={$smarty.get.p}" title="{$lang.preview}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
									{$lang.preview}
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