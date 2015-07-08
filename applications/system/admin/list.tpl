<section class="titre-profil admin" id="titre-page">
	<div class="container">
		<div class="row">
			<div class="span7" id="titre">
				<h1 class="page-title">{$lang.settings}</h1>
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
						<th>{$lang.name}</th>
						<th>{$lang.value}</th>		
						<th width="150"></th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$data item=d}
						<tr>
							<td>
								{if $d.help}
									{$d.help}
								{else}
									{$d.name}
								{/if}
							</td>
							<td>{$d.value}</td>
							<td>
								<a href="?m={$smarty.get.m}&c=edit&id={$d.id}" title="{$lang.edit}">
									<img src="{$data_server}/admin/images/icons/16x16/edit.png" />
									{$lang.edit}
								</a>
							</td>					
						</tr>
					{/foreach}
				</tbody>
			</table>
		{/if}
	</div>
</section>