<section id="titre-page" class="titre-page admin">
	<div class="container">
		<div class="row">
			<div id="titre" class="span7">
				<h1 class="page-title">{$title}</h1>
			</div>
			<div id="breadcrumbs" class="span5">
				<a href="/admin">{$lang.home}</a> \
			</div>
		</div>
	</div>
</section>

<div class="dashboard container" id="tab-membre">
	<ul>
		<li {if $smarty.get.type == 'system'}class="selected"{/if}>
			<div></div>
			<a href="/admin/?m=announcements&amp;c=list&amp;type=system">{$lang.system}</a>
		</li>
		<li class="{if $smarty.get.type == 'authors'}selected{/if} last">
			<div></div>
			<a href="/admin/?m=announcements&amp;c=list&amp;type=authors">{$lang.authors}</a>
			<div class="last"></div>
		</li>
	</ul>
</div>

<section id="conteneur">
	<div class="container">
		<div class="row">
			<div class="span12">
				{if $paging != ""}
					<div class="page-controls">
						{$paging}
					</div>
				{/if}

				{if is_array($data)}
					<table class="table-grey">
						<thead>
							<tr>
								<th>{$lang.name}</th>
								{if $smarty.get.type != 'system'}
									<th>{$lang.message}</th>
								{/if}
								{if $smarty.get.type != 'authors'}
									<th>{$lang.url}</th>
									<th>{$lang.photo}</th>
								{/if}
								{if $smarty.get.type != 'system'}
									<th>{$lang.date}</th>
								{/if}
								<th>{$lang.visible}</th>
								<th width="150"></th>
							</tr>
						</thead>

						<tbody>
						{foreach from=$data item=d}
							<tr id="row{$d.id}">
								<td>{$d.name}</td>
								{if $smarty.get.type != 'system'}
									<td>{$d.message}</td>
								{/if}
								{if $smarty.get.type != 'authors'}
									<td>{$d.url}</td>
									<td style="background: #263235;">
										{if $d.photo != ''}
											<img src="{$data_server}uploads/announcements/{$d.photo}" alt="{$d.name}" style="max-width: 500px;" />
										{/if}
									</td>
								{/if}
								{if $smarty.get.type != 'system'}
									<td>{$d.datetime|date_format:"%e %B %Y"}</td>
								{/if}
								<td>
									{if $d.visible == 'true'}
										<img src="{$data_server}admin/images/icons/24x24/accept.png" alt="" />
									{else}
										<img src="{$data_server}admin/images/icons/24x24/delete.png" alt="" />
									{/if}
								</td>							
								<td>
									<a href="?m={$smarty.get.m}&c=edit&id={$d.id}" title="{$lang.edit}">
										<img src="{$data_server}admin/images/icons/16x16/edit.png" />
										{$lang.edit}
									</a><br />
									{if $smarty.get.type == 'authors'}
										<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.id}{literal}'}{/literal},'deleteRow');">
											<img src="{$data_server}admin/images/icons/16x16/delete.png" />
											{$lang.delete}
										</a>
									{/if}
								</td>					
							</tr>
							{/foreach}
						</tbody>
					</table>
				{else}
					{$lang.no_records}	
				{/if}

				{if $smarty.get.type != 'system'}
					<div class="form-submit">
						<button class="btn btn-big-shadow" onclick="window.location='?m={$smarty.get.m}&c=add';" type="button" style="height: 45px; width: 300px;">{$lang.add_announcement}</button>
					</div>
				{/if}
			</div>
		</div>
	</div>
</section>