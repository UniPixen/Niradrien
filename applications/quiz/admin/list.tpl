<section id="titre-page" class="titre-page admin">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.quiz}</h1>
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
		{if $paging !=""}
			<div class="page-controls">
				{$paging}
			</div>
		{/if}

		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.question}</th>
						<th>{$lang.sort}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>{$d.name}</td>
							<td>
								<a href="?m={$smarty.get.m}&c=list&down={$d.id}&p={$smarty.get.p}" title="{$lang.up}">
									<img src="{$data_server}admin/images/icons/16x16/up.png" />
								</a>
								<a href="?m={$smarty.get.m}&c=list&up={$d.id}&p={$smarty.get.p}" title="{$lang.down}">
									<img src="{$data_server}admin/images/icons/16x16/download1.png" />
								</a>				
							</td>
							<td>
								<a href="?m={$smarty.get.m}&c=answers&id={$d.id}" title="{$lang.answers}">
									<img src="{$data_server}admin/images/icons/16x16/answers.png" />
									{$lang.answers}
								</a><br />
								<a href="?m={$smarty.get.m}&c=edit&id={$d.id}&p={$smarty.get.p}" title="{$lang.edit}">
									<img src="{$data_server}admin/images/icons/16x16/edit.png" />
									{$lang.edit}
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

		<div class="form-submit" style="margin-top: 30px;">
			<button onclick="window.location='?m={$smarty.get.m}&c=add';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 270px;">{$lang.add_new}</button>
		</div>
	</div>
</section>