<section class="titre-page admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.the_team}</h1>
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
		{if is_array($data)}
			<table class="table-grey">
				<thead>
					<tr>
						<th>{$lang.member}</th>
						<th>{$lang.role}</th>
						<th>{$lang.role} {$lang.english}</th>
						<th>{$lang.photo}</th>
						<th>{$lang.sort}</th>
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr id="row{$d.member_id}">
							<td>{$d.username} <small>({$d.firstname} {$d.lastname})</small></td>
							<td>{$d.role}</td>
							<td>{$d.role_en}</td>
							<td>
								{if $d.photo != ''}
									<img src="{$data_server}uploads/team/{$d.photo}" alt="{$d.firstname} {$d.lastname}" style="max-width: 32px;" />
								{/if}
							</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=team&amp;down={$d.member_id}" title="{$lang.up}">
									<img src="{$data_server}admin/images/icons/16x16/up.png" />
								</a>
								<a href="?m={$smarty.get.m}&amp;c=team&amp;up={$d.member_id}" title="{$lang.down}">
									<img src="{$data_server}admin/images/icons/16x16/download1.png" />
								</a>
							</td>
							<td>
								<a href="?m={$smarty.get.m}&amp;c=edit&amp;member_id={$d.member_id}" title="{$lang.edit}">
									<img src="{$data_server}admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a><br />
								<a href="javascript:void(0);" title="{$lang.delete}" onclick="eAjax('/applications/{$smarty.get.m}/ajax/delete.php',{literal}{'delete':true,'id':'{/literal}{$d.member_id}{literal}'}{/literal},'deleteRow');">
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
			<button onclick="window.location='?m={$smarty.get.m}&amp;c=add';" type="button" class="btn btn-big-shadow" style="height: 45px; width: 170px;">
				{$lang.add}
			</button>
		</div>
	</div>
</section>